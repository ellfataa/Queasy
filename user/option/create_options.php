<?php
    require_once("config.php");
    session_start();
    if(!isset($_SESSION["login"])){
        header("Location: login.php");
    }
    if(!isset($_GET["id"])){
        header("Location: create_quiz.php");
    }
    $question_id = $_GET["id"];
    $quiz_id = $_GET["quiz_id"];

if(isset($_POST["submit"])){
    $option_text = mysqli_real_escape_string($mysqli, $_POST["option_text"]);
    $is_correct = isset($_POST["is_correct"]) ? 1 : 0;
    $result = mysqli_query($mysqli, "INSERT INTO options (option_text, is_answer, question_id) VALUES ('$option_text', '$is_correct', '$question_id')");
    if($result){
        $_SESSION['success'] = "Option created successfully!";
    } else {
        $_SESSION['error'] = "Failed to create option: " . mysqli_error($mysqli);
    }
} else if(isset($_POST["next"])){
    $option_text = mysqli_real_escape_string($mysqli, $_POST["option_text"]);
    $is_correct = isset($_POST["is_correct"]) ? 1 : 0;
    $result = mysqli_query($mysqli, "INSERT INTO options (option_text, is_answer, question_id) VALUES ('$option_text', '$is_correct', '$question_id')");
    if($result){
        header("Location: create_questions.php?quiz_id=$quiz_id");
        exit;
    } else {
        $_SESSION['error'] = "Failed to create option: " . mysqli_error($mysqli);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Options | Queasy</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">
    
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
        
        .creation-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 700px;
            border: none;
        }
        
        .form-title {
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
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(106, 90, 205, 0.25);
        }
        
        .option-item {
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .option-item:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #5a4cb3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 90, 205, 0.3);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background-color: #e67e5f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 140, 102, 0.3);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-label {
            font-weight: 500;
            color: #495057;
        }
        
        .form-check-input {
            width: 20px;
            height: 20px;
            margin-top: 0;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .creation-card {
                padding: 1.5rem;
            }
            
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .button-group .btn {
                width: 100%;
                max-width: 250px;
            }
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    
    <div class="container">
        <div class="creation-card">
            <h2 class="form-title">Create Options</h2>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="" method="post">
                <div class="option-item">
                    <div class="mb-3">
                        <label for="option_text" class="form-label">Option Text</label>
                        <input type="text" class="form-control" id="option_text" name="option_text" required>
                    </div>
                    
                    <div class="checkbox-container mb-3">
                        <input class="form-check-input" type="checkbox" id="is_correct" name="is_correct" value="1">
                        <label class="checkbox-label" for="is_correct">This is the correct answer</label>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Add Option
                    </button>
                    <button type="submit" name="next" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i> Next Question
                    </button>
                    <a href="<?php echo $_SESSION["last_url"] ?? 'my_quizzes.php'; ?>" class="btn btn-outline">
                        <i class="fas fa-check me-2"></i> Done
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>