<?php
session_start();
require_once(__DIR__ . "../../../layout/functions.php");
if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}
$id = $_SESSION["id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuisku | Queasy</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../index.css">
    <link rel="stylesheet" href="my_quiz.css">
    
</head>
<body>
    <?php include("../../layout/navbar.php"); ?>
    
    <?php
    $creator = mysqli_query($mysqli, "SELECT * FROM user WHERE id = $id");
    $row_creator = mysqli_fetch_assoc($creator);
    ?>
    
    <div class="creator-header">
        <div class="container">
            <h1 class="creator-title">Kuisku</h1>
            <p class="creator-subtitle">Kreator: <?php echo htmlspecialchars($row_creator["username"]); ?></p>
        </div>
    </div>
    
    <div class="container">
        <?php
        $result = mysqli_query($mysqli, "SELECT * FROM quizzes WHERE creator_id = $id");
        $quiz_count = mysqli_num_rows($result);
        
        if ($quiz_count > 0): ?>
            <div class="quiz-grid">
                <?php while($row = mysqli_fetch_assoc($result)):
                    $category = mysqli_query($mysqli, "SELECT * FROM category where id = $row[category_id]");
                    $row_category = mysqli_fetch_assoc($category);
                ?>
                    <div class="quiz-card">
                        <img src="../../img/<?php echo htmlspecialchars($row_category['img']); ?>" class="quiz-image" alt="<?php echo htmlspecialchars($row_category['category_name']); ?>">
                        <div class="quiz-body">
                            <h3 class="quiz-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <span class="quiz-category"><?php echo htmlspecialchars($row_category['category_name']); ?></span>
                            <div class="quiz-actions">
                                <a href="edit_quiz.php?id=<?php echo $row['id']; ?>" class="edit-btn">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <span class="text-muted small">
                                    <i class="fas fa-question-circle me-1"></i> 
                                    <?php 
                                        $question_count = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM questions WHERE quiz_id = {$row['id']}");
                                        $question_data = mysqli_fetch_assoc($question_count);
                                        echo $question_data['count'] . ' Pertanyaan';
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-question"></i>
                </div>
                <h3>Belum Ada Kuis</h3>
                <p>Anda belum membuat kuis apa pun. Mulailah dengan membuat kuis pertama Anda!</p>
                <a href="create_quiz.php" class="btn create-btn">
                    <i class="fas fa-plus me-2"></i> Buat Kuis
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>