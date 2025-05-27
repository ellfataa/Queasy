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
        
        /* Enhanced Confetti Animations */
        @keyframes confettiFall {
            0% {
                transform: translateY(-20px) rotate(0deg);
                opacity: 1;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
        
        @keyframes confettiBurst {
            0% {
                transform: translate(-50%, -50%) rotate(0deg) scale(0);
                opacity: 1;
            }
            50% {
                opacity: 1;
                transform: translate(-50%, -50%) 
                          translate(calc(cos(var(--angle)) * var(--distance)), calc(sin(var(--angle)) * var(--distance))) 
                          rotate(180deg) scale(1);
            }
            100% {
                opacity: 0;
                transform: translate(-50%, -50%) 
                          translate(calc(cos(var(--angle)) * var(--distance)), calc(sin(var(--angle)) * var(--distance))) 
                          rotate(360deg) scale(0.5);
            }
        }
        
        .confetti {
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }
        
        .confetti-burst {
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        
        /* Pulse animation for score circle */
        @keyframes scorePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .score-circle.animate {
            animation: scorePulse 2s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <?php include('../../layout/navbar.php'); ?>
    
    <div class="result-header">
        <div class="container">
            <h1 class="section-title result-title text-white">Hasil Kuis</h1>
            <p class="section-description lead">Lihat Performamu, Pahlawan!</p>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class="container my-4">
        <div class="result-container">
            <div class="result-card card shadow-sm border-0">
                <!-- Score Display Section -->
                <div class="score-display text-center">
                    <div class="score-circle animate">
                        <div class="score-value"><?php echo round($score, 1); ?>%</div>
                    </div>
                    <h3 class="score-text fw-bold">Skor Kamu</h3>
                    <p class="text-muted mb-0">Kamu berhasil menjawab <?php echo $correct; ?> dari <?php echo $total_questions; ?></p>
                </div>
                
                <!-- Message Box -->
                <div class="message-box rounded-3 p-3">
                    <?php
                        if ($score >= 80) {
                            echo '<h3 class="message-title fw-bold">🏆 Kemenangan Gemilang! 🏆</h3>';
                            echo '<p class="mb-0">Kamu berhasil menaklukkan Tantangan Pengetahuan dan keluar sebagai Pahlawan Kebijaksanaan. Gerbang selanjutnya akan terbuka, Selesaikan!</p>';
                        } else if ($score >= 60) {
                            echo '<h3 class="message-title fw-bold">✨ Penemuan Langka! ✨</h3>';
                            echo '<p class="mb-0">Kamu menjelajahi Hutan Pertanyaan dan menemukan artefak langka. Item ini akan membantumu membuka gerbang tantangan berikutnya.</p>';
                        } else if ($score >= 40) {
                            echo '<h3 class="message-title fw-bold">📖 Langkah Awal 📖</h3>';
                            echo '<p class="mb-0">Petualanganmu baru dimulai. Meski sempat tersandung, kamu telah memperoleh pengalaman berharga untuk pertarungan selanjutnya.</p>';
                        } else if ($score >= 20) {
                            echo '<h3 class="message-title fw-bold">🔥 Usaha Berani 🔥</h3>';
                            echo '<p class="mb-0">Meskipun rintangan menghadang, kamu tetap melangkah maju. Terus belajar dan persiapkan dirimu untuk kembali ke medan laga!</p>';
                        } else {
                            echo '<h3 class="message-title fw-bold">💤 Petualang Jatuh Tertidur 💤</h3>';
                            echo '<p class="mb-0">Ruang tantangan terlalu berat kali ini. Tapi setiap pahlawan yang jatuh akan bangkit kembali. Istirahatlah, berlatih, dan coba lagi!</p>';
                        }
                    ?>
                </div>
                
                <!-- Stats Container dengan Bootstrap Grid -->
                <div class="row g-3 stats-container">
                    <div class="col-md-4">
                        <div class="stat-box card h-100 text-center p-3 border-0">
                            <div class="stat-value fw-bold"><?php echo $correct; ?></div>
                            <div class="stat-label text-muted">Jawaban Benar</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box card h-100 text-center p-3 border-0">
                            <div class="stat-value fw-bold"><?php echo $total_questions - $correct; ?></div>
                            <div class="stat-label text-muted">Jawaban Salah</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box card h-100 text-center p-3 border-0">
                            <div class="stat-value fw-bold"><?php echo $lives_remaining; ?></div>
                            <div class="stat-label text-muted">Sisa Nyawa</div>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Container -->
                <div class="progress-container">
                    <div class="progress-label d-flex justify-content-between mb-2">
                        <span class="fw-semibold">Akurasimu</span>
                        <span class="fw-semibold"><?php echo round($score, 1); ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $score; ?>%" 
                            aria-valuenow="<?php echo $score; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <!-- Lives Display -->
                <div class="lives-display d-flex justify-content-center gap-2 mb-4">
                    <?php for($i = 0; $i < $lives_remaining; $i++): ?>
                        <i class="fas fa-heart life"></i>
                    <?php endfor; ?>
                    <?php for($i = 0; $i < (3 - $lives_remaining); $i++): ?>
                        <i class="fas fa-heart text-muted"></i>
                    <?php endfor; ?>
                </div>
                
                <!-- Action Button -->
                <div class="text-center">
                    <a href="/Queasy/index.php" class="btn action-btn">
                        Coba Stage Lain <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Enhanced Confetti Animation - Continuous looping version
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration for different score ranges
            const confettiConfig = {
                high: { // Score >= 80%
                    colors: ['#FFD700', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8'],
                    particlesPerWave: 15,
                    interval: 300,
                    intensity: 'high'
                },
                medium: { // Score >= 60%
                    colors: ['#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD'],
                    particlesPerWave: 10,
                    interval: 500,
                    intensity: 'medium'
                },
                low: { // Score < 60%
                    colors: ['#96CEB4', '#FFEAA7', '#DDA0DD', '#FFB6C1'],
                    particlesPerWave: 6,
                    interval: 800,
                    intensity: 'low'
                }
            };

            // Get score from PHP
            const score = <?php echo round($score, 1); ?>;
            
            // Determine confetti type based on score
            let config;
            if (score >= 80) {
                config = confettiConfig.high;
            } else if (score >= 60) {
                config = confettiConfig.medium;
            } else {
                config = confettiConfig.low;
            }

            // Create a single wave of confetti particles
            function createConfettiWave() {
                const container = document.body;
                
                for(let i = 0; i < config.particlesPerWave; i++) {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    
                    // Random properties
                    const size = Math.random() * 8 + 4; // 4-12px
                    const left = Math.random() * 100;
                    const animationDuration = Math.random() * 4 + 3; // 3-7 seconds
                    const rotation = Math.random() * 360;
                    const color = config.colors[Math.floor(Math.random() * config.colors.length)];
                    
                    // Apply styles
                    confetti.style.cssText = `
                        position: fixed;
                        width: ${size}px;
                        height: ${size}px;
                        background-color: ${color};
                        left: ${left}vw;
                        top: -20px;
                        border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                        animation: confettiFall ${animationDuration}s linear forwards;
                        transform: rotate(${rotation}deg);
                        pointer-events: none;
                        z-index: 1000;
                    `;
                    
                    container.appendChild(confetti);
                    
                    // Remove confetti after animation
                    setTimeout(() => {
                        if (confetti.parentNode) {
                            confetti.parentNode.removeChild(confetti);
                        }
                    }, animationDuration * 1000 + 500);
                }
            }

            // Special burst effect for high scores (also looping)
            function createSpecialConfettiBurst() {
                const container = document.body;
                
                // Create burst effect from center
                for(let i = 0; i < 8; i++) {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti-burst';
                    
                    const angle = (i / 8) * 360;
                    const distance = Math.random() * 150 + 80;
                    const size = Math.random() * 10 + 6;
                    const color = config.colors[Math.floor(Math.random() * config.colors.length)];
                    
                    confetti.style.cssText = `
                        position: fixed;
                        width: ${size}px;
                        height: ${size}px;
                        background-color: ${color};
                        left: 50vw;
                        top: 50vh;
                        border-radius: 50%;
                        animation: confettiBurst 2s ease-out forwards;
                        transform-origin: center;
                        pointer-events: none;
                        z-index: 1001;
                        --angle: ${angle}deg;
                        --distance: ${distance}px;
                    `;
                    
                    container.appendChild(confetti);
                    
                    setTimeout(() => {
                        if (confetti.parentNode) {
                            confetti.parentNode.removeChild(confetti);
                        }
                    }, 2500);
                }
            }

            // Start continuous confetti animation
            let confettiInterval;
            let burstInterval;

            function startConfettiLoop() {
                // Create initial wave immediately
                createConfettiWave();
                
                // Set up continuous interval
                confettiInterval = setInterval(() => {
                    createConfettiWave();
                }, config.interval);

                // Special burst effect for high scores
                if (score >= 80) {
                    // Initial burst
                    setTimeout(() => createSpecialConfettiBurst(), 500);
                    
                    // Continuous burst every 3 seconds
                    burstInterval = setInterval(() => {
                        createSpecialConfettiBurst();
                    }, 3000);
                }
            }

            // Function to stop confetti (if needed)
            function stopConfetti() {
                if (confettiInterval) {
                    clearInterval(confettiInterval);
                }
                if (burstInterval) {
                    clearInterval(burstInterval);
                }
            }

            // Start the confetti loop
            startConfettiLoop();

            // Optional: Add click event to toggle confetti on/off
            document.addEventListener('keydown', function(e) {
                if (e.key === 'c' || e.key === 'C') {
                    if (confettiInterval) {
                        stopConfetti();
                        confettiInterval = null;
                        burstInterval = null;
                        console.log('Confetti stopped. Press C again to restart.');
                    } else {
                        startConfettiLoop();
                        console.log('Confetti restarted!');
                    }
                }
            });

            // Animate progress bar
            setTimeout(() => {
                const progressBar = document.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.style.transition = 'width 2s ease-in-out';
                    progressBar.style.width = '<?php echo $score; ?>%';
                }
            }, 500);
            
            // Animate stat values
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach((stat, index) => {
                setTimeout(() => {
                    stat.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        stat.style.transform = 'scale(1)';
                    }, 200);
                }, 800 + (index * 200));
            });

            // Optional: Stop confetti when user leaves the page (to prevent memory leaks)
            window.addEventListener('beforeunload', function() {
                stopConfetti();
            });

            // Optional: Pause confetti when page is not visible (performance optimization)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopConfetti();
                } else {
                    if (!confettiInterval) {
                        startConfettiLoop();
                    }
                }
            });
        });
    </script>
</body>
</html>