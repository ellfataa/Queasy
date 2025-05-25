<?php
session_start();
require_once(__DIR__ . "../../../layout/functions.php");

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$category_id = (int)$_GET["id"];

// Ambil data kategori
$category_query = mysqli_query($mysqli, "SELECT * FROM category WHERE id = $category_id");
$category = mysqli_fetch_assoc($category_query);

if (!$category) {
    $_SESSION['error'] = "Kategori tidak ditemukan.";
    header("Location: /Queasy/index.php");
    exit;
}

// Ambil daftar kuis dalam kategori, diurutkan berdasarkan ID
$quizzes_query = mysqli_query($mysqli, "
    SELECT * FROM quizzes 
    WHERE category_id = $category_id 
    ORDER BY id ASC
");

$quizzes = [];
while ($quiz = mysqli_fetch_assoc($quizzes_query)) {
    $quizzes[] = $quiz;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['category_name']); ?> - Queasy</title>

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <!-- Font css -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />
    <!-- icon css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="../../index.css" />
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">

    <style>
        .quiz-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .quiz-card.locked {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .quiz-card.locked:hover {
            transform: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .quiz-status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .quiz-score-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }

        .card-img-overlay-custom {
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.3) 100%);
        }

        .category-header {
            background: linear-gradient(135deg, #fcc822 0%, #f39c12 100%);
            border-radius: 15px;
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .progress-indicator {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 1rem;
        }

        .quiz-difficulty {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
        }

        @media (max-width: 768px) {
            .category-header {
                padding: 1.5rem;
                text-align: center;
            }
            
            .quiz-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include('../../layout/navbar.php'); ?>

    <div class="container my-4">
        <!-- Category Header -->
        <div class="category-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="fas fa-th-large me-2"></i>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </h1>
                    <p class="mb-0">
                        <?php echo htmlspecialchars($category['description'] ?? 'Kumpulan kuis menarik dalam kategori ini'); ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="progress-indicator text-center">
                        <?php
                        $completed_quizzes = 0;
                        $total_quizzes = count($quizzes);
                        
                        foreach ($quizzes as $quiz) {
                            if (has_user_attempted_quiz($mysqli, $user_id, $quiz['id'])) {
                                $completed_quizzes++;
                            }
                        }
                        
                        $progress_percentage = $total_quizzes > 0 ? ($completed_quizzes / $total_quizzes) * 100 : 0;
                        ?>
                        <div class="mb-2">
                            <h4 class="mb-1"><?php echo $completed_quizzes; ?>/<?php echo $total_quizzes; ?></h4>
                            <small>Kuis Selesai</small>
                        </div>
                        <div class="progress" style="height: 8px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-light" style="width: <?php echo $progress_percentage; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mb-4">
            <a href="/Queasy/index.php" class="btn btn-warning text-white">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>

        <?php
        // Tampilkan pesan error jika ada
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
            echo htmlspecialchars($_SESSION['error']);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
            echo "</div>";
            unset($_SESSION['error']);
        }

        // Tampilkan pesan sukses jika ada
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
            echo htmlspecialchars($_SESSION['success']);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
            echo "</div>";
            unset($_SESSION['success']);
        }
        ?>

        <?php if (empty($quizzes)): ?>
            <div class="text-center py-5">
                <i class="fas fa-question fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">Belum Ada Kuis</h3>
                <p class="text-muted">Kuis dalam kategori ini akan segera tersedia.</p>
                <a href="/Queasy/index.php" class="btn btn-warning">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php
                foreach ($quizzes as $index => $quiz) {
                    $quiz_id = $quiz['id'];
                    
                    // Gunakan fungsi yang sudah ada di functions.php untuk konsistensi
                    $is_locked = is_quiz_locked($mysqli, $user_id, $quiz_id, $category_id);
                    $quiz_score = get_user_quiz_score($mysqli, $user_id, $quiz_id);
                    $has_attempted = has_user_attempted_quiz($mysqli, $user_id, $quiz_id);

                    // Tentukan alasan terkunci jika perlu
                    $lock_reason = '';
                    if ($is_locked) {
                        if (is_category_locked($mysqli, $user_id, $category_id)) {
                            $previous_category_id = $category_id - 1;
                            $lock_reason = 'Selesaikan kategori sebelumnya dengan minimal 50% untuk membuka kategori ini';
                        } else {
                            // Kuis dalam kategori terkunci karena kuis sebelumnya belum selesai/lulus
                            if ($index > 0) {
                                $previous_quiz_id = $quizzes[$index - 1]['id'];
                                $previous_attempted = has_user_attempted_quiz($mysqli, $user_id, $previous_quiz_id);
                                
                                if (!$previous_attempted) {
                                    $lock_reason = 'Selesaikan kuis sebelumnya terlebih dahulu';
                                } else {
                                    $previous_score = get_user_quiz_score($mysqli, $user_id, $previous_quiz_id);
                                    if ($previous_score < 50) {
                                        $lock_reason = 'Dapatkan minimal 50% pada kuis sebelumnya';
                                    }
                                }
                            }
                        }
                    }

                    echo '<div class="col-lg-4 col-md-6 col-sm-12">';
                    
                    if ($is_locked) {
                        echo '<div class="quiz-card card locked h-100">';
                    } else {
                        echo '<a href="quiz.php?id='.$quiz_id.'" class="text-decoration-none h-100 d-block">';
                        echo '<div class="quiz-card card h-100">';
                    }
                    
                    // Card Image dengan Overlay
                    echo '<div class="position-relative" style="height: 200px;">';
                    
                    // Background image atau warna default
                    if (!empty($category['img'])) {
                        echo '<img src="../../img/'.htmlspecialchars($category['img']).'" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="'.htmlspecialchars($quiz['title']).'">';
                    } else {
                        echo '<div class="h-100 w-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">';
                        echo '<i class="fas fa-question fa-3x text-white opacity-50"></i>';
                        echo '</div>';
                    }
                    
                    echo '<div class="card-img-overlay-custom"></div>';
                    
                    // Status Badges
                    if ($has_attempted) {
                        $badge_class = $quiz_score >= 50 ? 'bg-success' : 'bg-warning';
                        $badge_text = $quiz_score >= 50 ? 'Lulus' : 'Perlu Diperbaiki';
                        echo '<div class="quiz-score-badge">';
                        echo '<span class="badge '.$badge_class.' rounded-pill px-2 py-1">'.round($quiz_score).'%</span>';
                        echo '</div>';
                        echo '<div class="quiz-status-badge">';
                        echo '<span class="badge '.$badge_class.' rounded-pill px-2 py-1">'.$badge_text.'</span>';
                        echo '</div>';
                    } elseif ($is_locked) {
                        echo '<div class="quiz-status-badge">';
                        echo '<span class="badge bg-secondary rounded-pill px-2 py-1"><i class="fas fa-lock"></i></span>';
                        echo '</div>';
                    } else {
                        echo '<div class="quiz-status-badge">';
                        echo '<span class="badge bg-primary rounded-pill px-2 py-1">Tersedia</span>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                    
                    // Card Body
                    echo '<div class="card-body d-flex flex-column">';
                    echo '<h5 class="card-title mb-2" style="color: #18152d; font-weight: 600;">'.htmlspecialchars($quiz['title']).'</h5>';
                    
                    $description = $quiz['description'] ?? 'Kuis menarik untuk menguji pengetahuanmu dalam topik ini.';
                    echo '<p class="card-text text-muted mb-3 flex-grow-1" style="font-size: 0.9rem;">'.htmlspecialchars(substr($description, 0, 100)).'...</p>';
                    
                    // Quiz Info
                    echo '<div class="quiz-info mt-auto">';
                    
                    if ($is_locked) {
                        echo '<div class="text-center py-2">';
                        echo '<i class="fas fa-lock text-muted mb-2"></i>';
                        echo '<p class="text-muted mb-0 small">'.$lock_reason.'</p>';
                        echo '</div>';
                    } else {
                        echo '<div class="d-flex justify-content-between align-items-center pt-2 border-top">';
                        echo '<small class="text-muted">';
                        echo '<i class="fas fa-play-circle me-1"></i>';
                        if ($has_attempted) {
                            echo $quiz_score >= 50 ? 'Ulangi Kuis' : 'Perbaiki Skor';
                        } else {
                            echo 'Mulai Kuis';
                        }
                        echo '</small>';
                        echo '<small class="text-warning fw-semibold">';
                        echo '<i class="fas fa-arrow-right"></i>';
                        echo '</small>';
                        echo '</div>';
                    }
                    
                    echo '</div>'; // quiz-info
                    echo '</div>'; // card-body
                    echo '</div>'; // card
                    
                    if (!$is_locked) {
                        echo '</a>';
                    }
                    
                    echo '</div>'; // col
                }
                ?>
            </div>
        <?php endif; ?>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"
    ></script>

    <script>
        // Prevent click on locked cards
        document.querySelectorAll('.quiz-card.locked').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
        });
    </script>
</body>
</html>