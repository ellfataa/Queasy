<?php
session_start();
require_once("config.php");
if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
$quiz_id = $_GET["id"];
$quiz_query = mysqli_query($mysqli, "SELECT q.*, c.category_name 
                                   FROM quizzes q 
                                   JOIN category c ON q.category_id = c.id 
                                   WHERE q.id = '$quiz_id'");
$quiz = mysqli_fetch_assoc($quiz_query);

$success_message = '';
$error_message = '';

if(isset($_POST["submit"]) || isset($_POST["next"])) {
    $title = mysqli_real_escape_string($mysqli, $_POST["title"]);
    $desc = mysqli_real_escape_string($mysqli, $_POST["desc"]);
    $category = (int)$_POST["category"];
    
    $query = "UPDATE quizzes SET title = '$title', description = '$desc', category_id = '$category' WHERE id = '$quiz_id'";
    if(mysqli_query($mysqli, $query)) {
        $success_message = '<div class="alert alert-success alert-dismissible fade show" style="border-left: 5px solid #28a745;">
                              <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                  <h5 class="alert-heading mb-1">Quiz Updated Successfully!</h5>
                                  <p class="mb-0">Your quiz changes have been saved.</p>
                                </div>
                              </div>
                              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>';
        
        // If "Next" button was clicked, redirect to questions page
        if(isset($_POST["next"])) {
            header("Location: my_questions.php?id=$quiz_id");
            exit;
        }
    } else {
        $error_message = '<div class="alert alert-danger alert-dismissible fade show" style="border-left: 5px solid #dc3545;">
                            <div class="d-flex align-items-center">
                              <i class="fas fa-exclamation-circle me-3" style="font-size: 1.5rem;"></i>
                              <div>
                                <h5 class="alert-heading mb-1">Update Failed</h5>
                                <p class="mb-0">'.mysqli_error($mysqli).'</p>
                              </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                          </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz | Queasy</title>

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
            --danger-color: #e74a3b;
            --info-color: #17a2b8;
            --light-bg: #f9f5ff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7ff;
        }
        
        .quiz-header {
            background: linear-gradient(135deg, var(--primary-color), #8a6de9);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            text-align: center;
        }
        
        .quiz-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .edit-card {
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
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(106, 90, 205, 0.25);
        }
        
        textarea.form-control {
            min-height: 120px;
        }
        
        .save-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .save-btn:hover {
            background-color: #5a4cb3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 90, 205, 0.3);
        }
        
        .next-btn {
            background-color: var(--info-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .next-btn:hover {
            background-color: #138496;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
        }
        
        .delete-btn {
            background-color: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .delete-btn:hover {
            background-color: #c23a2d;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 74, 59, 0.3);
        }
        
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
        }
        
        .alert {
            border-radius: 10px;
            border-left: 5px solid;
        }
        
        .alert-success {
            border-left-color: #28a745;
        }
        
        .alert-danger {
            border-left-color: #dc3545;
        }
        
        @media (max-width: 768px) {
            .quiz-title {
                font-size: 1.8rem;
            }
            
            .edit-card {
                padding: 1.5rem;
            }
            
            .btn-container {
                flex-direction: column;
            }
            
            .btn-group {
                flex-direction: column;
                width: 100%;
            }
            
            .save-btn, .next-btn, .delete-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    
    <div class="quiz-header">
        <div class="container">
            <h1 class="quiz-title">Edit Quiz</h1>
            <p class="lead">Update your quiz details</p>
        </div>
    </div>
    
    <div class="container">
        <div class="edit-card">
            <?php 
            // Display success message
            if(!empty($success_message)) {
                echo $success_message;
            }
            
            // Display error message
            if(!empty($error_message)) {
                echo $error_message;
            }
            ?>
            
            <form action="" method="post">
                <div class="mb-4">
                    <label for="title" class="form-label">Quiz Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($quiz["title"]); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="desc" class="form-label">Description</label>
                    <textarea class="form-control" id="desc" name="desc" rows="4" required><?php echo htmlspecialchars($quiz["description"]); ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <?php
                        $category_query = mysqli_query($mysqli, "SELECT * FROM category");
                        while($category = mysqli_fetch_assoc($category_query)):
                        ?>
                            <option value="<?php echo $category["id"]; ?>" <?php echo ($category["id"] == $quiz["category_id"]) ? "selected" : ""; ?>>
                                <?php echo htmlspecialchars($category["category_name"]); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="btn-container">
                    <a href="delete_quiz.php?id=<?php echo $quiz_id ?>" class="delete-btn text-decoration-none text-center" onclick="return confirm('Apakah Anda yakin ingin menghapus quiz ini? Semua pertanyaan dan jawaban terkait juga akan dihapus.');">
                        <i class="fas fa-trash me-2"></i> Hapus Quiz
                    </a>
                    
                    <div class="btn-group">
                        <button type="submit" name="submit" class="save-btn">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        
                        <button type="submit" name="next" class="next-btn">
                            <i class="fas fa-arrow-right me-2"></i> Lanjut
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>