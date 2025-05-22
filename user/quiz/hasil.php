<?php
require_once(__DIR__ . "../../../layout/functions.php");
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

function count_questions($quiz_id) {
    global $mysqli;
    $count_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM questions WHERE quiz_id = $quiz_id");
    $count = mysqli_fetch_assoc($count_query);
    return $count['total'];
}

// Ambil nilai dari session
$correct = (int)($_SESSION['correct_answers'] ?? 0);
$quiz_id = (int)($_SESSION['quiz_id'] ?? 0); // ambil dari session, bukan GET
$total_questions = count_questions($quiz_id);
$score = ($total_questions > 0) ? ($correct / $total_questions) * 100 : 0;
$lives_remaining = (int)($_SESSION['lives'] ?? 0);

// Reset session kuis (setelah nilai dihitung)
unset(
    $_SESSION['lives'],
    $_SESSION['current_question'],
    $_SESSION['correct_answers'],
    $_SESSION['questions_answered'],
    $_SESSION['score'],
    $_SESSION['quiz_id']
);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Quiz</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">
    <link rel="stylesheet" href="hasil.css">
    <style>
        .score-circle {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: conic-gradient(var(--success-color) <?php echo $score ?>%,#e0e0e0 <?php echo $score ?>% 100%);
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
        position: relative;
        }
    </style>
</head>
<body>
    <?php include('../../layout/navbar.php'); ?>
    
    <div class="result-header">
        <div class="container">
            <h1 class="result-title">Hasil Kuis</h1>
            <p class="lead">Lihat Performamu, Pahlawan!</p>
        </div>
    </div>
    
    <div class="result-container">
        <div class="result-card">
            <div class="score-display">
                <div class="score-circle">
                    <div class="score-value"><?php echo round($score, 1); ?>%</div>
                </div>
                <h3 class="score-text">Skor Kamu</h3>
                <p class="text-muted">Kamu berhasil menjawab <?php echo $correct; ?> dari <?php echo $total_questions; ?></p>
            </div>
            
            <div class="message-box">
                <?php
                    if ($score >= 80) {
                        echo '<h3 class="message-title">🏆 Kemenangan Gemilang! 🏆</h3>';
                        echo '<p>Kamu berhasil menaklukkan Tantangan Pengetahuan dan keluar sebagai Pahlawan Kebijaksanaan. Gerbang selanjutnya akan terbuka, Selesaikan!</p>';
                    } else if ($score >= 60) {
                        echo '<h3 class="message-title">✨ Penemuan Langka! ✨</h3>';
                        echo '<p>Kamu menjelajahi Hutan Pertanyaan dan menemukan artefak langka. Item ini akan membantumu membuka gerbang tantangan berikutnya.</p>';
                    } else if ($score >= 40) {
                        echo '<h3 class="message-title">📖 Langkah Awal 📖</h3>';
                        echo '<p>Petualanganmu baru dimulai. Meski sempat tersandung, kamu telah memperoleh pengalaman berharga untuk pertarungan selanjutnya.</p>';
                    } else if ($score >= 20) {
                        echo '<h3 class="message-title">🔥 Usaha Berani 🔥</h3>';
                        echo '<p>Meskipun rintangan menghadang, kamu tetap melangkah maju. Terus belajar dan persiapkan dirimu untuk kembali ke medan laga!</p>';
                    } else {
                        echo '<h3 class="message-title">💤 Petualang Jatuh Tertidur 💤</h3>';
                        echo '<p>Ruang tantangan terlalu berat kali ini. Tapi setiap pahlawan yang jatuh akan bangkit kembali. Istirahatlah, berlatih, dan coba lagi!</p>';
                    }
                ?>
            </div>
            
            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-value"><?php echo $correct; ?></div>
                    <div class="stat-label">Jawaban Benar</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value"><?php echo $total_questions - $correct; ?></div>
                    <div class="stat-label">Jawaban Salah</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value"><?php echo $lives_remaining; ?></div>
                    <div class="stat-label">Sisa Nyawa</div>
                </div>
            </div>
            
            <div class="progress-container">
                <div class="progress-label">
                    <span>Akurasimu</span>
                    <span><?php echo round($score, 1); ?>%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo $score; ?>%" 
                        aria-valuenow="<?php echo $score; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            
            <div class="lives-display">
                <?php for($i = 0; $i < $lives_remaining; $i++): ?>
                    <i class="fas fa-heart life"></i>
                <?php endfor; ?>
                <?php for($i = 0; $i < (3 - $lives_remaining); $i++): ?>
                    <i class="fas fa-heart" style="color: #ccc;"></i>
                <?php endfor; ?>
            </div>
            
            <div class="button">
                <a href="index.php" class="btn action-btn">
                Coba Stage Lain <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if($score >= 80): ?>
    <script>
        // Create confetti for high scores
        document.addEventListener('DOMContentLoaded', function() {
            const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];
            const container = document.body;
            
            for(let i = 0; i < 100; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                confetti.style.animationDelay = Math.random() * 5 + 's';
                container.appendChild(confetti);
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>