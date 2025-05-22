<?php
session_start();
require_once(__DIR__ . "../../../layout/config.php");
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
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">
    <link rel="stylesheet" href="edit_quiz.css">
</head>
<body>
    <?php include("../../layout/navbar.php"); ?>
    
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