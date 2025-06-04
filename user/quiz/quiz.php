<?php
session_start();
require_once(__DIR__ . "../../../layout/functions.php");

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$quiz_id = (int)$_GET['id'];
$_SESSION['quiz_id'] = $quiz_id;

// Ambil data kuis saat ini
$current_quiz_query = mysqli_query($mysqli, "SELECT * FROM quizzes WHERE id = $quiz_id");
$current_quiz = mysqli_fetch_assoc($current_quiz_query);

if (!$current_quiz) {
    $_SESSION['error'] = "Kuis tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$category_id = $current_quiz['category_id'];

// Periksa apakah kuis terkunci menggunakan fungsi dari functions.php
if (is_quiz_locked($mysqli, $user_id, $quiz_id, $category_id)) {
    if ($category_id == 1) {
        // Untuk kategori Matematika, seharusnya tidak terkunci
        // Jika sampai di sini ada bug di fungsi is_quiz_locked
        $_SESSION['error'] = "Terjadi kesalahan sistem. Silakan coba lagi.";
    } else {
        $_SESSION['error'] = "Anda harus menyelesaikan kuis sebelumnya dengan skor minimal 50% untuk mengakses kuis ini.";
    }
    header("Location: view_category.php?id=$category_id");
    exit;
}

// Inisialisasi nyawa dan progress
if (!isset($_SESSION['lives'])) {
    $_SESSION['lives'] = 3;
    $_SESSION['current_question'] = 0;
    $_SESSION['correct_answers'] = 0;
    $_SESSION['questions_answered'] = [];
}

$quizid = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($quizid === 0) {
    die("Quiz ID tidak valid.");
}

// Ambil quiz dan user info
$result = mysqli_query($mysqli, "SELECT * FROM quizzes WHERE id = $quizid");
if (!$result || mysqli_num_rows($result) === 0) {
    die("Quiz tidak ditemukan.");
}
$row = mysqli_fetch_assoc($result);
$user_query = mysqli_query($mysqli, "SELECT * FROM user WHERE id = {$row['creator_id']}");
$user = mysqli_fetch_assoc($user_query);
$title = $row["title"];
$desc = $row["description"];

// Ambil semua pertanyaan
$questions_query = mysqli_query($mysqli, "SELECT * FROM questions WHERE quiz_id = $quizid ORDER BY id");
$all_questions = [];
while ($q = mysqli_fetch_assoc($questions_query)) {
    $all_questions[] = $q;
}
if (empty($all_questions)) {
    die("Tidak ada pertanyaan dalam kuis ini.");
}

// Ambil pertanyaan saat ini
$current_q = $all_questions[$_SESSION['current_question']] ?? null;

// Ambil cerita jika masih ada pertanyaan
$story = null;
if ($current_q) {
    $is_correct = $_SESSION['answer_status'] ?? null;

    if ($is_correct === 'correct') {
        $story_query = mysqli_query($mysqli, "SELECT * FROM story_segments 
            WHERE quiz_id = $quizid AND question_id = {$current_q['id']} AND show_on_correct = 1 LIMIT 1");
    } elseif ($is_correct === 'wrong') {
        $story_query = mysqli_query($mysqli, "SELECT * FROM story_segments 
            WHERE quiz_id = $quizid AND question_id = {$current_q['id']} AND show_on_wrong = 1 LIMIT 1");
    }

    // Periksa apakah query berhasil dan ada hasil
    if (isset($story_query)) {
        if ($story_query && mysqli_num_rows($story_query) > 0) {
            $story = mysqli_fetch_assoc($story_query);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Queasy - Kuis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Style & Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="quiz.css">
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">
</head>
<body>
<?php include('../../layout/navbar.php'); ?>

<div class="container my-4">
    <form action="check_answer.php" method="post">
        <!-- Modal jawaban -->
        <div class="modal fade" id="answerModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?php if (isset($_SESSION['answer_status'])): ?>
                                <?php echo $_SESSION['answer_status'] === 'correct' ? 'Benar!🎉' : 'Oops! Salah!'; ?>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="modal-body text-center">
                        <?php if (isset($_SESSION['answer_status'])): 
                            $is_correct = $_SESSION['answer_status'] === 'correct';
                            $should_show_story = ($is_correct && $story['show_on_correct']) || (!$is_correct && $story['show_on_wrong']);
                        ?>
                            <?php if ($is_correct): ?>
                                <div class="correct-answer">
                                    <i class="fas fa-check-circle modal-icon"></i>
                                    <p class="fs-4 fw-bold">Kamu Hebat!</p>
                                    <p class="fs-5">+1 point!</p>
                                </div>
                            <?php else: ?>
                                <div class="wrong-answer">
                                    <i class="fas fa-times-circle modal-icon"></i>
                                    <p class="fs-4 fw-bold">Kamu Tersandung😢</p>
                                    <p class="mt-3">Jawaban yang benar adalah:<strong><?php echo htmlspecialchars($_SESSION['last_correct_answer']); ?></strong></p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($story['story_text'])): ?>
                                <div class="story-box mt-4 text-start">
                                    <h5 class="story-title">📖 Lanjutan Cerita...</h5>
                                    <p><?php echo nl2br(htmlspecialchars($story['story_text'])); ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <?php 
                            $is_last_question = ($_SESSION['current_question'] >= count($all_questions) );
                            if (!isset($_SESSION['lives']) || $_SESSION['lives'] <= 0 || $is_last_question): ?>
                                <div class="text-center">
                                    <?php if ($_SESSION['lives'] <= 0): ?>
                                        <p class="text-danger fw-bold fs-5">Oh tidak! Nyawamu sudah habis! ❤️‍🩹</p>
                                    <?php else: ?>
                                        <p class="text-success fw-bold fs-5">Akhirnya Kisah Berakhir!</p>
                                    <?php endif; ?>
                                    <a href="hasil.php" class="btn btn-warning btn-lg">Lihat Hasilnya</a>
                                </div>
                            <?php else: ?>
                                <button type="button" class="continue-btn btn-lg" data-bs-dismiss="modal">
                                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            <?php endif; 
                        ?>
                </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($current_q): ?>
        <div class="container mt-4 quiz-container">
            <input type="hidden" name="quiz_id" value="<?php echo $quizid; ?>">
            <input type="hidden" name="question_id" value="<?php echo $current_q['id']; ?>">

            <!-- Quiz Header Section -->
            <div class="hero-section bg-white p-4 mb-4 rounded-3" style="box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); border: 3px solid var(--primary-color);">
                <div class="text-center">
                    <h1 class="hero-title mb-3" style="font-size: clamp(1.5rem, 4vw, 2.5rem); font-weight: 700; color: var(--primary-color);">
                        <?php echo htmlspecialchars($title); ?>
                    </h1>
                    <p class="hero-text mb-3" style="font-size: 16px; color: #666; line-height: 1.6;">
                        <?php echo nl2br(htmlspecialchars($desc)); ?>
                    </p>
                    <p class="creator text-primary" style="font-style: italic; font-size: 14px;">
                        <i class="fas fa-user me-1"></i>Kreator: <?php echo htmlspecialchars($user['username']); ?>
                    </p>
                </div>
            </div>

            <!-- Lives Container -->
            <div class="lives-container d-flex justify-content-center align-items-center mb-4">
                <span class="lives-text me-3" style="font-weight: bold; color: var(--primary-color);">Sisa Nyawa:</span>
                <div class="d-flex gap-2">
                    <?php for ($i = 0; $i < $_SESSION['lives']; $i++): ?>
                        <span class="life" style="color: var(--life-color); font-size: 1.5rem;">
                            <i class="fas fa-heart"></i>
                        </span>
                    <?php endfor; ?>
                    <?php for ($i = 0; $i < (3 - $_SESSION['lives']); $i++): ?>
                        <span class="life life-lost" style="color: #ccc; font-size: 1.5rem;">
                            <i class="fas fa-heart"></i>
                        </span>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Progress Container -->
            <div class="progress-container mb-4" style="background-color: #219ebc; border-radius: 10px; height: 20px; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);">
                <div class="progress-bar" role="progressbar"
                    style="width: <?php echo ($_SESSION['current_question'] / count($all_questions)) * 100; ?>%; background-color: var(--primary-color); border-radius: 10px; transition: width 0.5s ease; height: 100%;"
                    aria-valuenow="<?php echo $_SESSION['current_question']; ?>"
                    aria-valuemin="0"
                    aria-valuemax="<?php echo count($all_questions); ?>">
                </div>
            </div>

            <!-- Question Card -->
            <div class="card question-card bg-white border-0 shadow-sm" style="border-radius: 15px; border: 3px solid var(--secondary-color) !important; overflow: hidden; position: relative;">
                
                <!-- Question Header -->
                <div class="card-header bg-transparent border-0 p-4">
                    <h4 class="question-number mb-0 d-flex align-items-center" style="color: var(--primary-color); font-weight: bold; font-size: 1.2rem;">
                        <span class="question-badge me-3 d-inline-flex align-items-center justify-content-center" style="background-color: var(--primary-color); color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 1rem;">Q</span>
                        Pertanyaan <?php echo ($_SESSION['current_question'] + 1); ?> dari <?php echo count($all_questions); ?>
                    </h4>
                </div>
                
                <!-- Question Body -->
                <div class="card-body p-4">
                    <div class="question-text mb-4" style="font-size: 1.3rem; font-weight: 600; color: #444; line-height: 1.4;">
                        <?php echo htmlspecialchars($current_q['quest_text']); ?>
                    </div>

                    <!-- Options Container -->
                    <div class="options-container d-flex flex-column gap-3">
                        <?php 
                        $options_query = mysqli_query($mysqli, "SELECT * FROM options WHERE question_id = {$current_q['id']} ORDER BY id");
                        $alphabet = 'A';
                        while ($option = mysqli_fetch_assoc($options_query)): ?>
                            <label class="option card bg-light border-2 p-3 cursor-pointer" onclick="selectOption(this)" style="border-color: #e0d6ff !important; transition: all 0.3s ease; cursor: pointer;">
                                <input type="radio" name="answer" value="<?php echo $option['id']; ?>" required style="display: none;">
                                <div class="option-label d-flex align-items-center w-100">
                                    <span class="option-letter d-inline-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background-color: var(--primary-color); color: white; border-radius: 50%; font-weight: bold;">
                                        <?php echo $alphabet++; ?>
                                    </span>
                                    <span style="font-size: 1.1rem;">
                                        <?php echo htmlspecialchars($option['option_text']); ?>
                                    </span>
                                </div>
                            </label>
                        <?php endwhile; ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-warning btn-lg px-4 py-2" style="border-radius: 50px; font-weight: bold; box-shadow: 0 4px 10px rgba(106, 90, 205, 0.3); transition: all 0.3s ease;">
                            <i class="fas fa-paper-plane me-2"></i> Jawab
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function selectOption(clickedOption) {
        // Remove selection from all options
        const allOptions = document.querySelectorAll('.option');
        allOptions.forEach(option => {
            option.classList.remove('selected');
            option.style.backgroundColor = '';
            option.style.borderColor = '#e0d6ff';
            option.style.transform = '';
            option.style.boxShadow = '';
        });
        
        // Add selection to clicked option
        clickedOption.classList.add('selected');
        clickedOption.style.backgroundColor = '#d4c7ff';
        clickedOption.style.borderColor = 'var(--primary-color)';
        clickedOption.style.transform = 'scale(1.02)';
        clickedOption.style.boxShadow = '0 4px 12px rgba(106, 90, 205, 0.2)';
        
        // Update option letter styling
        const optionLetter = clickedOption.querySelector('.option-letter');
        optionLetter.style.backgroundColor = 'var(--primary-color)';
        optionLetter.style.transform = 'scale(1.1)';
        
        // Mark the radio button as checked
        const radioInput = clickedOption.querySelector('input[type="radio"]');
        radioInput.checked = true;
        
        // Add pulse animation
        clickedOption.style.animation = 'pulse 0.5s ease';
        setTimeout(() => {
            clickedOption.style.animation = '';
        }, 500);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight selected option when page loads (if returning to question)
        const selectedRadio = document.querySelector('input[name="answer"]:checked');
        if (selectedRadio) {
            const selectedOption = selectedRadio.closest('.option');
            if (selectedOption) {
                selectOption(selectedOption);
            }
        }
        
        // Add hover effects to options
        const options = document.querySelectorAll('.option');
        options.forEach(option => {
            option.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected')) {
                    this.style.backgroundColor = '#e0d6ff';
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.1)';
                }
            });
            
            option.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected')) {
                    this.style.backgroundColor = '';
                    this.style.transform = '';
                    this.style.boxShadow = '';
                }
            });
        });
    });

document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['answer_status'])): ?>
    var answerModal = new bootstrap.Modal(document.getElementById('answerModal'));
    answerModal.show();
    
    // Play sound based on answer
    const sound = new Audio('<?php echo $_SESSION['answer_status'] === 'correct' ? "correct.mp3" : "wrong.mp3" ?>');
    sound.play().catch(function(error) {
        // Handle audio play errors gracefully
        console.log('Audio play failed:', error);
    });
    
    <?php unset($_SESSION['answer_status'], $_SESSION['last_correct_answer'], $_SESSION['quiz_finished']); ?>
    <?php endif; ?>
    
    // Prevent form submission if no option is selected
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!document.querySelector('input[name="answer"]:checked')) {
            e.preventDefault();
            Swal.fire({
                title: 'Oops!',
                text: 'Silakan pilih jawaban sebelum mengirim!',
                icon: 'warning',
                confirmButtonColor: '#fcc822',
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>

</body>
</html>