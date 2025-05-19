<div class="container">
    <div class="row d-flex justify-content-between">
        <div class="col-8 m-3 py-3 px-4 bg-white rounded-4 shadow-sm">
            <h1>Hai, <?php echo htmlspecialchars($_SESSION["username"]); ?>!👋</h1>
            <p>Ayo Mulai Petualanganmu!</p>
        </div>
        <div class="col-3 d-flex flex-column align-items-center my-3 p-3 bg-white rounded-4 shadow-sm">
            <p>Buat Kuismu Sendiri!</p>
            <a href="create_quiz.php"><button class="btn btn-warning">+ Tambah</button></a>
        </div>
        <div class="category">
            <h3>Kategori :</h3>
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

            echo '<div class="title d-flex justify-content-between align-items-center">';
            echo "<h4>".htmlspecialchars($row['category_name'])."</h4>";

            
                echo '<a href="view_category.php?id='.$categ_id.'" class="view-all text-black">Lihat semua</a>';
            

            echo '</div>';

            // Get quizzes for this category
            $result2 = mysqli_query($mysqli, "SELECT * FROM quizzes WHERE category_id = $categ_id LIMIT 4");
            
            if (mysqli_num_rows($result2) > 0) {
                echo '<div class="row">'; // Tambah row untuk kuis
                
                while($row2 = mysqli_fetch_assoc($result2)) {
                    echo '<div class="col-md-3 mb-5 mt-2 position-relative">';
                    
                    // Kuis pertama dalam kategori selalu terbuka
                    $is_first_quiz = ($row2["id"] == get_first_quiz_id($mysqli, $categ_id));
                    $is_quiz_locked = $is_category_locked && !$is_first_quiz;
                    
                    if (!$is_quiz_locked) {
                        echo '<a href="quiz.php?id='.$row2["id"].'" class="text-decoration-none">';
                    }
                    
                    echo '<div class="kartu card shadow" style="width: 16rem;">';
                    echo '<img src="img/'.htmlspecialchars($row['img']).'" class="card-img-top mt-2" alt="'.htmlspecialchars($row['category_name']).'">';
                    echo '<div class="card-body">';
                    echo "<p class='card-text'>".htmlspecialchars($row2['title'])."</p>";
                    
                    // Tampilkan badge untuk kuis pertama
                    if ($is_first_quiz) {
                        echo '<span class="badge bg-primary">Pembuka</span>';
                    }
                    
                    echo '</div>';
                    echo '</div>';
                    
                    if (!$is_quiz_locked) {
                        echo '</a>';
                    }
                    
                    // Overlay untuk kuis terkunci
                    // if ($is_quiz_locked) {
                    //     echo '<div class="locked-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-light opacity-75 rounded" style="z-index: 2;">';
                    //     echo '<i class="fas fa-lock fa-2x text-danger mb-2"></i>';
                    //     echo '<p class="text-center text-danger fw-bold">Selesaikan Kuis Sebelumnya</p>';
                    //     echo '</div>';
                    // }
                    
                    echo '</div>';
                }
                
                echo '</div>'; // Tutup row kuis
            } else {
                echo '<div class="col-12 text-muted">Belum ada kuis dalam kategori ini.</div>';
            }
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

<!-- <style>
    .locked-overlay {
        cursor: not-allowed;
        transition: all 0.3s ease;
    }
    .locked-overlay:hover {
        opacity: 0.85;
    }
    .kartu {
        transition: transform 0.2s;
        height: 100%;
    }
    .kartu:hover {
        transform: translateY(-5px);
    }
    .badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .view-all {
        color: #0d6efd;
        text-decoration: none;
        font-size: 0.9rem;
    }
    .view-all:hover {
        text-decoration: underline;
    }
</style> -->