<?php
require_once("config.php");
session_start();
if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET["id"])) {
    header("Location: create_question.php");
    exit;
}

$id = (int)$_GET["id"];
$result = mysqli_query($mysqli, "SELECT * FROM questions WHERE id = '$id'");
$row = mysqli_fetch_array($result);

if(isset($_POST["submit"])) {
    $quest_text = mysqli_real_escape_string($mysqli, $_POST["quest_text"]);
    $result = mysqli_query($mysqli, "UPDATE questions SET quest_text = '$quest_text' WHERE id = '$id'");
    
    if($result) {
        $_SESSION['success'] = "Question has been updated successfully!";
        header("Location: my_questions.php?id=".$row['quiz_id']);
        exit;
    } else {
        $error = "Failed to update question: " . mysqli_error($mysqli);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question | Queasy</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --primary-color: #6a5acd;
            --secondary-color: #ff8c66;
            --light-bg: #f9f5ff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: #333;
        }
        
        .edit-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 700px;
            border: none;
        }
        
        .edit-title {
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            transition: all 0.3s;
            width: 100%;
            min-height: 120px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(106, 90, 205, 0.25);
        }
        
        .btn-edit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
            display: block;
            margin: 1.5rem auto 0;
        }
        
        .btn-edit:hover {
            background-color: #5a4cb3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 90, 205, 0.3);
        }
        
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .back-link:hover {
            color: #5a4cb3;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 10px;
            border-left: 5px solid #dc3545;
        }
        
        @media (max-width: 768px) {
            .edit-card {
                padding: 1.5rem;
            }
            
            .edit-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    
    <div class="container">
        <div class="edit-card">
            <h2 class="edit-title">Edit Question</h2>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="" method="post">
                <div class="mb-4">
                    <label for="quest_text" class="form-label">Question Text</label>
                    <textarea class="form-control" id="quest_text" name="quest_text" required><?php echo htmlspecialchars($row["quest_text"]); ?></textarea>
                </div>
                
                <button type="submit" name="submit" class="btn-edit">
                    <i class="fas fa-save me-2"></i> Update Question
                </button>
                
                <a href="my_questions.php?id=<?php echo $row['quiz_id']; ?>" class="back-link">
                    <i class="fas fa-arrow-left me-1"></i> Back to Questions
                </a>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>