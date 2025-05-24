<?php
    session_start();
    if (!isset($_SESSION["login"])) {
        header("Location: /Queasy/index.php");
        exit;
    }
?>

<div class="container my-4">
    <!-- Welcome Section -->
    <div class="row">
        <div class="col-12">
            <div class="hero-section bg-white p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="hero-title mb-3" style="font-size: 3.2 rem; font-weight: 700; color: #18152d;">
                            Hai, <?php echo htmlspecialchars($_SESSION["username"]); ?>! 👋
                        </h1>
                        <p class="hero-text mb-0" style="font-size: 18px; color: #666;">
                            Ayo mulai petualanganmu dan uji pengetahuanmu dengan berbagai kategori kuis menarik!
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="achievement-badge p-3 rounded-3" style="background: rgba(252, 200, 34, 0.2); border: 2px solid #fcc822;">
                            <i class="fas fa-star fa-2x text-warning mb-2"></i>
                            <p class="mb-0 fw-semibold" style="color: #18152d;">Mulai Bermain!</p>
                            <small class="text-muted">Pilih kategori favoritmu</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Categories Section -->
    <div class="categories-section">
        <div class="section-header mb-4">
            <h3 class="section-title" style="color: #18152d; font-weight: 600; font-size: 1.75rem;">
                <i class="fas fa-th-large me-2 text-warning"></i> Kategori Kuis
            </h3>
        </div>

        <?php
        $result = mysqli_query($mysqli, "SELECT * FROM category ORDER BY id ASC");
        
        while ($row = mysqli_fetch_assoc($result)) {
            $categ_id = $row["id"];
            
            // Kategori pertama selalu terbuka
            $is_category_locked = ($categ_id > 1);
            
            if ($is_category_locked) {
                $prev_category_id = $categ_id - 1;
                $prev_score = get_user_category_score($mysqli, $_SESSION['id'], $prev_category_id);
                $is_category_locked = ($prev_score < 50);
            }

            echo '<div class="category-block mb-5">';
            
            // Category Header
            echo '<div class="category-header d-flex justify-content-between align-items-center mb-3 p-3 rounded-3" style="background: rgba(252, 200, 34, 0.1); border-left: 4px solid #fcc822;">';
            echo '<div class="category-title-section">';
            echo '<h4 class="category-name mb-1" style="color: #18152d; font-weight: 600;">'.htmlspecialchars($row['category_name']).'</h4>';
            
            if ($is_category_locked) {
                echo '<small class="text-muted"><i class="fas fa-lock me-1"></i> Selesaikan kategori sebelumnya untuk membuka</small>';
            } else {
                echo '<small class="text-success"><i class="fas fa-unlock me-1"></i> Tersedia untuk dimainkan</small>';
            }
            
            echo '</div>';
            echo '<a href="/Queasy/user/category/view_category.php?id='.$categ_id.'" class="btn btn-outline-warning btn-sm px-3" style="border-radius: 20px; font-weight: 500;">';
            echo '<i class="fas fa-eye me-1"></i> Lihat Semua';
            echo '</a>';
            echo '</div>';

            // Get quizzes for this category
            $result2 = mysqli_query($mysqli, "SELECT * FROM quizzes WHERE category_id = $categ_id LIMIT 4");
            
            if (mysqli_num_rows($result2) > 0) {
                echo '<div class="row g-4 quiz-cards-container">'; 
                
                while($row2 = mysqli_fetch_assoc($result2)) {
                    echo '<div class="col-lg-3 col-md-6 col-sm-12">';
                    
                    // Kuis pertama dalam kategori selalu terbuka
                    $is_first_quiz = ($row2["id"] == get_first_quiz_id($mysqli, $categ_id));
                    $is_quiz_locked = $is_category_locked && !$is_first_quiz;
                    
                    echo '<div class="quiz-card-wrapper position-relative h-100">';
                    
                    if (!$is_quiz_locked) {
                        echo '<a href="user/quiz/quiz.php?id='.$row2["id"].'" class="text-decoration-none h-100 d-block">';
                    }
                    
                    echo '<div class="quiz-card card h-100 shadow-sm border-0" style="transition: all 0.3s ease; border-radius: 15px; overflow: hidden;'.($is_quiz_locked ? ' opacity: 0.6;' : '').'">';
                    
                    // Card Image
                    echo '<div class="card-img-container position-relative" style="height: 180px; overflow: hidden;">';
                    echo '<img src="img/'.htmlspecialchars($row['img']).'" class="card-img-top h-100 w-100" style="object-fit: cover; transition: transform 0.3s ease;" alt="'.htmlspecialchars($row['category_name']).'">';
                    
                    // Badges overlay
                    echo '<div class="position-absolute top-0 end-0 p-2">';
                    if ($is_first_quiz) {
                        echo '<span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.75rem;">Pembuka</span>';
                    }
                    if ($is_quiz_locked) {
                        echo '<span class="badge bg-secondary rounded-pill px-2 py-1 ms-1" style="font-size: 0.75rem;"><i class="fas fa-lock"></i></span>';
                    }
                    echo '</div>';
                    echo '</div>';
                    
                    // Card Body
                    echo '<div class="card-body p-3 d-flex flex-column">';
                    echo '<h6 class="card-title mb-2" style="color: #18152d; font-weight: 600; font-size: 0.95rem; line-height: 1.4;">'.htmlspecialchars($row2['title']).'</h6>';
                    echo '<p class="card-text text-muted mb-2 flex-grow-1" style="font-size: 0.85rem; line-height: 1.3;">'.htmlspecialchars(substr($row2['description'] ?? 'Kuis menarik untuk menguji pengetahuanmu', 0, 80)).'...</p>';
                    
                    // Quiz stats or status
                    echo '<div class="quiz-meta d-flex justify-content-between align-items-center mt-auto pt-2 border-top">';
                    echo '<small class="text-muted"><i class="fas fa-questions-circle me-1"></i>Kuis</small>';
                    if (!$is_quiz_locked) {
                        echo '<small class="text-success fw-semibold">Mulai <i class="fas fa-arrow-right ms-1"></i></small>';
                    } else {
                        echo '<small class="text-muted">Terkunci</small>';
                    }
                    echo '</div>';
                    
                    echo '</div>';
                    echo '</div>';
                    
                    if (!$is_quiz_locked) {
                        echo '</a>';
                    }
                    
                    echo '</div>'; // quiz-card-wrapper
                    echo '</div>'; // col
                }
                
                echo '</div>'; // row
            } else {
                echo '<div class="no-quiz-placeholder text-center py-5 rounded-3" style="background: rgba(108, 117, 125, 0.1);">';
                echo '<i class="fas fa-quiz fa-3x text-muted mb-3"></i>';
                echo '<p class="text-muted mb-0">Belum ada kuis dalam kategori ini</p>';
                echo '<small class="text-muted">Kuis akan segera tersedia</small>';
                echo '</div>';
            }
            
            echo '</div>'; // category-block
        }
        
        // Fungsi bantuan untuk mendapatkan ID kuis pertama dalam kategori
        function get_first_quiz_id($mysqli, $category_id) {
            $query = mysqli_query($mysqli, "SELECT id FROM quizzes WHERE category_id = $category_id ORDER BY id ASC LIMIT 1");
            $result = mysqli_fetch_assoc($query);
            return $result ? $result['id'] : null;
        }
        ?>
    </div>
</div>

<style>
/* Quiz Card Hover Effects */
.quiz-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.quiz-card:hover .card-img-top {
    transform: scale(1.05);
}

.quiz-card-wrapper:not(.disabled) .quiz-card {
    cursor: pointer;
}

/* Category Block Styling */
.category-block {
    margin-bottom: 3rem;
}

.category-header {
    transition: all 0.3s ease;
}

.category-header:hover {
    background: rgba(252, 200, 34, 0.15) !important;
    transform: translateX(5px);
}

/* Achievement Badge Animation */
.achievement-badge {
    transition: all 0.3s ease;
}

.achievement-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(252, 200, 34, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem !important;
    }
    
    .hero-text {
        font-size: 16px !important;
    }
    
    .category-header {
        flex-direction: column;
        text-align: center;
    }
    
    .category-header .btn {
        margin-top: 1rem;
    }
    
    .quiz-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .hero-section {
        text-align: center;
    }
    
    .achievement-badge {
        margin-top: 1rem;
    }
    
    .section-title {
        font-size: 1.5rem !important;
    }
}

/* Loading Animation for Future Use */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.loading-pulse {
    animation: pulse 1.5s infinite;
}
</style>