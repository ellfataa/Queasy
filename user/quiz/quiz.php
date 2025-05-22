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

// Ambil daftar kuis dalam kategori yang sama, diurutkan berdasarkan ID
$quizzes_query = mysqli_query($mysqli, "
    SELECT id FROM quizzes 
    WHERE category_id = $category_id 
    ORDER BY id ASC
");

$quiz_ids = [];
while ($quiz = mysqli_fetch_assoc($quizzes_query)) {
    $quiz_ids[] = $quiz['id'];
}

// Temukan posisi kuis saat ini dalam daftar
$current_index = array_search($quiz_id, $quiz_ids);

if ($current_index === false) {
    $_SESSION['error'] = "Kuis tidak valid.";
    header("Location: index.php");
    exit;
}

// Jika bukan kuis pertama, periksa skor pada kuis sebelumnya
if ($current_index > 0) {
    $previous_quiz_id = $quiz_ids[$current_index - 1];
    
    // Hitung jumlah pertanyaan di kuis sebelumnya
    $total_questions_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM questions WHERE quiz_id = $previous_quiz_id");
    $total_questions_data = mysqli_fetch_assoc($total_questions_query);
    $total_questions = $total_questions_data['total'];
    
    // Hitung skor pengguna pada kuis sebelumnya
    $score_query = mysqli_query($mysqli, "
        SELECT SUM(ua.is_correct) AS correct_answers
        FROM questions q
        JOIN user_answers ua ON ua.question_id = q.id
        WHERE q.quiz_id = $previous_quiz_id AND ua.user_id = $user_id
    ");
    $score_data = mysqli_fetch_assoc($score_query);
    $correct_answers = (int)($score_data['correct_answers'] ?? 0);
    
    // Hitung persentase skor
    $previous_quiz_score = ($total_questions > 0) ? ($correct_answers / $total_questions) * 100 : 0;

    if ($previous_quiz_score < 50) {
        $_SESSION['error'] = "Anda harus menyelesaikan kuis sebelumnya dengan skor minimal 50% untuk mengakses kuis ini.";
        header("Location: view_category.php?id=$category_id");
        exit;
    }
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
<form action="check_answer.php" method="post">
    <?php include("../../layout/navbar.php"); ?>

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

        <div class="quiz-header">
            <h1 class="quiz-title"><?php echo htmlspecialchars($title); ?></h1>
            <p class="quiz-description"><?php echo nl2br(htmlspecialchars($desc)); ?></p>
            <p class="creator">Kreator: <?php echo htmlspecialchars($user['username']); ?></p>
        </div>

        <!-- Nyawa -->
        <div class="lives-container">
            <span class="lives-text">Sisa Nyawa:</span>
            <?php for ($i = 0; $i < $_SESSION['lives']; $i++): ?>
                <span class="life"><i class="fas fa-heart"></i></span>
            <?php endfor; ?>
            <?php for ($i = 0; $i < (3 - $_SESSION['lives']); $i++): ?>
                <span class="life life-lost"><i class="fas fa-heart"></i></span>
            <?php endfor; ?>
        </div>

        <!-- Progress -->
        <div class="progress-container">
            <div class="progress-bar" role="progressbar"
                 style="width: <?php echo ($_SESSION['current_question'] / count($all_questions)) * 100; ?>%"
                 aria-valuenow="<?php echo $_SESSION['current_question']; ?>"
                 aria-valuemin="0"
                 aria-valuemax="<?php echo count($all_questions); ?>">
            </div>
        </div>

        <!-- Soal -->
        <div class="question-card">
            
                <?php 
                    echo "<h4 class=question-number>Pertanyaan " . ($_SESSION['current_question'] + 1) . " dari " . count($all_questions) . "</h4>";
                ?>
            
            <div class="question-text"><?php echo htmlspecialchars($current_q['quest_text']); ?></div>

            <!-- Opsi -->
            <div class="options-container">
                <?php 
                $options_query = mysqli_query($mysqli, "SELECT * FROM options WHERE question_id = {$current_q['id']} ORDER BY id");
                $alphabet = 'A';
                while ($option = mysqli_fetch_assoc($options_query)): ?>
                    <label class="option" onclick="selectOption(this)">
                        <input type="radio" name="answer" value="<?php echo $option['id']; ?>" required>
                        <span class="option-label">
                            <span class="option-letter"><?php echo $alphabet++; ?></span>
                            <?php echo htmlspecialchars($option['option_text']); ?>
                        </span>
                    </label>
                <?php endwhile; ?>
            </div>

            <div class="text-center">
                <button type="submit" class="btn submit-btn">
                    <i class="fas fa-paper-plane me-2"></i> Jawab
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</form>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function selectOption(clickedOption) {
        // Remove selection from all options
        const allOptions = document.querySelectorAll('.option');
        allOptions.forEach(option => {
            option.classList.remove('selected');
        });
        
        // Add selection to clicked option
        clickedOption.classList.add('selected');
        
        // Mark the radio button as checked
        const radioInput = clickedOption.querySelector('input[type="radio"]');
        radioInput.checked = true;
        
        // Add slight bounce effect
        clickedOption.style.transform = 'scale(1.05)';
        setTimeout(() => {
            clickedOption.style.transform = 'scale(1)';
        }, 200);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight selected option when page loads (if returning to question)
        const selectedRadio = document.querySelector('input[name="answer"]:checked');
        if (selectedRadio) {
            const selectedOption = selectedRadio.closest('.option');
            if (selectedOption) {
                selectedOption.classList.add('selected');
            }
        }
    });
document.addEventListener('DOMContentLoaded', function() {
    // Highlight selected option
    const options = document.querySelectorAll('.option');
    options.forEach(option => {
        option.addEventListener('click', function() {
            options.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
    
    <?php if (isset($_SESSION['answer_status'])): ?>
    var answerModal = new bootstrap.Modal(document.getElementById('answerModal'));
    answerModal.show();
    
    // Play sound based on answer
    const sound = new Audio('<?php echo $_SESSION['answer_status'] === 'correct' ? "correct.mp3" : "wrong.mp3" ?>');
    sound.play();
    
    <?php unset($_SESSION['answer_status'], $_SESSION['last_correct_answer'], $_SESSION['quiz_finished']); ?>
    <?php endif; ?>
    
    // Prevent form submission if no option is selected
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!document.querySelector('input[name="answer"]:checked')) {
            e.preventDefault();
            Swal.fire({
                title: 'Oops!',
                text: 'Please select an answer before submitting!',
                icon: 'warning',
                confirmButtonColor: '#6a5acd'
            });
        }
    });
});
</script>

</body>
</html>
