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
    <title>View Category</title>

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"
      integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv"
      crossorigin="anonymous"
    />
    <!-- Font css -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900& display=swap"
      rel="stylesheet"
    />
    <!-- icon css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <link rel="stylesheet" href="../../index.css" />
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">

</head>
<body>
    <?php include('../../layout/navbar.php'); ?>

    <div class="container my-4">
        <h1><?php echo htmlspecialchars($category['category_name']); ?></h1>

        <?php
        // Tampilkan pesan error jika ada
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
            unset($_SESSION['error']);
        }
        ?>

        <div class="row">
            <?php
            $previous_quiz_score = null;
            foreach ($quizzes as $index => $quiz) {
                $quiz_id = $quiz['id'];
                $is_locked = false;

                // Ganti logika pengecekan skor:
                if ($index > 0) {
                    $previous_quiz_id = $quizzes[$index - 1]['id'];
                    
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
                        $is_locked = true;
                    }
                }

                echo '<div class="col-md-3 mb-4">';
                if ($is_locked) {
                    echo '<div class="card text-center bg-light">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">'.htmlspecialchars($quiz['title']).'</h5>';
                    echo '<p class="card-text text-danger">Terkunci</p>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<a href="quiz.php?id='.$quiz_id.'" class="text-decoration-none">';
                    echo '<div class="card text-center">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">'.htmlspecialchars($quiz['title']).'</h5>';
                    echo '<p class="card-text">Mulai Kuis</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"
></script>
</body>
</html>