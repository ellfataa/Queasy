<?php
require_once("config.php");
session_start();
if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET["quiz_id"])) {
    header("Location: my_question.php");
    exit;
}

$quiz_id = $_GET["quiz_id"];

if(isset($_POST["submit"])) {
    $quest_text = mysqli_real_escape_string($mysqli, $_POST["quest_text"]);

    if (!empty($quest_text)) {
        $result = mysqli_query($mysqli, "INSERT INTO questions (quest_text, quiz_id) VALUES ('$quest_text', '$quiz_id')");
        
        if($result) {
            $new_question_id = $mysqli->insert_id;
            header("Location: my_questions.php?id=$quiz_id");
            exit;
        } else {
            $error = "Failed to create question: " . mysqli_error($mysqli);
        }
    } else {
        $error = "Question text cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Question | Queasy</title>

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
            background-color: #f5f7ff;
        }
        
        .question-header {
            background: linear-gradient(135deg, var(--primary-color), #8a6de9);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            text-align: center;
        }
        
        .question-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .creation-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2rem;
            max-width: 700px;
            margin: 0 auto;
            border: none;
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
            min-height: 120px;
            width: 100%;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(106, 90, 205, 0.25);
        }
        
        .create-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
            display: block;
            margin: 2rem auto 0;
        }
        
        .create-btn:hover {
            background-color: #5a4cb3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 90, 205, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border-left: 5px solid #dc3545;
        }
        
        @media (max-width: 768px) {
            .question-title {
                font-size: 1.8rem;
            }
            
            .creation-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    
    <div class="question-header">
        <div class="container">
            <h1 class="question-title">Create New Question</h1>
            <p class="lead">Add a question to your quiz</p>
        </div>
    </div>
    
    <div class="container">
        <div class="creation-card">
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
                    <textarea class="form-control" id="quest_text" name="quest_text" required></textarea>
                </div>
                
                <button type="submit" name="submit" class="create-btn">
                    <i class="fas fa-plus-circle me-2"></i> Create Question 
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>