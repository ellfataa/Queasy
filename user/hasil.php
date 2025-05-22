<?php
require_once("functions.php");
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
    <link rel="icon" href="img/q!.ico" type="image/x-icon">
    <style>
        /* navbar */
        .logo {
            font-size: 32px;
            font-style: normal;
            font-weight: 800;
            margin-bottom: 0px;
            color: #000000;
        }
        .logo span {
            color: #fcc822;
        }
        .nav-item {
            font-size: 14px;
        }
        .nav-link {
            color: #000000;
            text-decoration: none;
        }
        .nav-item button {
            font-size: 14px;
        }
        .nav-item a:hover {
            color: #fcc822;
            text-decoration: underline;
            transition: 0.3s;
        }
        .logo:hover {
            color: white;
        }
        .navbar{
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            background-color: white;
        }
        /* navbar end */

        :root {
            --primary-color: #fcc822;
            --secondary-color: #ff8c66;
            --success-color: #4caf50;
            --warning-color: #ffc107;
            --danger-color: #f44336;
            --light-color: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7ff;
            color: #333;
        }
        
        .result-header {
            background: linear-gradient(135deg, var(--primary-color), #8a6de9);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 30px 30px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(106, 90, 205, 0.3);
        }

        .result-header::before {
            content: "";
            position: absolute;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .result-header::after {
            content: "";
            position: absolute;
            bottom: 300px;
            right: -30px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .result-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2.8rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .result-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .result-card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            border: none;
        }
        
        .result-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }
        
        .score-display {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .score-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: conic-gradient(
                var(--success-color) <?php echo $score ?>%, 
                #e0e0e0 <?php echo $score ?>% 100%
            );
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
            position: relative;
        }
        
        .score-circle::before {
            content: "";
            position: absolute;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background-color: white;
        }
        
        .score-value {
            position: relative;
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            z-index: 1;
        }
        
        .score-text {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .message-box {
            background-color: #f8f5ff;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
            border-left: 5px solid var(--primary-color);
        }
        
        .message-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .stats-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        
        .stat-box {
            text-align: center;
            padding: 1.5rem;
            border-radius: 15px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin: 0.5rem;
            flex: 1;
            min-width: 150px;
            border-top: 4px solid var(--primary-color);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }
        
        .progress-container {
            margin-bottom: 2rem;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
            background-color: #e0e0e0;
        }
        
        .progress-bar {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border-radius: 5px;
        }
        
        .lives-display {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        
        .life {
            color: var(--danger-color);
            font-size: 1.8rem;
        }
        
        .action-btn {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(106, 90, 205, 0.3);
            transition: all 0.3s;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            display: block;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(106, 90, 205, 0.4);
            color: white;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f00;
            border-radius: 50%;
            animation: fall 5s linear infinite;
        }
        
        @keyframes fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
        
        @media (max-width: 768px) {
            .result-title {
                font-size: 2.2rem;
            }
            
            .score-circle {
                width: 150px;
                height: 150px;
            }
            
            .score-circle::before {
                width: 130px;
                height: 130px;
            }
            
            .score-value {
                font-size: 2.5rem;
            }
            
            .message-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    
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