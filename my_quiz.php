<?php
session_start();
require_once("functions.php");
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
        .navbar{
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            background-color: white;
        }
        /* navbar end */
        :root {
            --primary-color: #ffc822;
            --secondary-color: #ff8c66;
            --light-bg: #f9f5ff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7ff;
            color: #333;
        }
        
        .creator-header {
            background: linear-gradient(135deg, var(--primary-color), #8a6de9);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .creator-header::before {
            content: "";
            position: absolute;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .creator-header::after {
            content: "";
            position: absolute;
            bottom: -80px;
            right: -30px;
            width: 250px;
            height: 250px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .creator-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .creator-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .quiz-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            border: none;
        }
        
        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(106, 90, 205, 0.2);
        }
        
        .quiz-image {
            height: 160px;
            object-fit: cover;
            width: 100%;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .quiz-body {
            padding: 1.5rem;
        }
        
        .quiz-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .quiz-category {
            display: inline-block;
            background: var(--light-bg);
            color: var(--primary-color);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }
        
        .quiz-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .edit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 15px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .edit-btn:hover {
            background-color: #5a4cb3;
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 0;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .empty-icon {
            font-size: 5rem;
            color: var(--primary-color);
            opacity: 0.5;
            margin-bottom: 1.5rem;
        }
        
        .create-btn {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 2rem;
            box-shadow: 0 5px 15px rgba(106, 90, 205, 0.3);
            transition: all 0.3s;
        }
        
        .create-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(106, 90, 205, 0.4);
            color: white;
        }
        
        @media (max-width: 768px) {
            .creator-title {
                font-size: 2rem;
            }
            
            .quiz-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    
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
                        <img src="img/<?php echo htmlspecialchars($row_category['img']); ?>" class="quiz-image" alt="<?php echo htmlspecialchars($row_category['category_name']); ?>">
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