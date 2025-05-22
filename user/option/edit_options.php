<?php
require_once("config.php");
session_start();
if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET["id"])) {
    header("Location: my_quizzes.php");
    exit;
}

$id = (int)$_GET["id"];
$result = mysqli_query($mysqli, "SELECT * FROM options WHERE id = '$id'");
$row = mysqli_fetch_assoc($result);
$quest_id = $row["question_id"];

if(isset($_POST["submit"])) {
    $option_text = mysqli_real_escape_string($mysqli, $_POST["option_text"]);
    $is_answer = isset($_POST["is_answer"]) ? 1 : 0;
    
    $sql = "UPDATE options SET option_text = '$option_text', is_answer = '$is_answer' WHERE id = '$id'";
    $result = mysqli_query($mysqli, $sql);
    
    if($result) {
        $_SESSION['success'] = "Option updated successfully!";
        header("Location: my_options.php?id=$quest_id");
        exit;
    } else {
        $error = "Failed to update option: " . mysqli_error($mysqli);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Option | Quiz System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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
            --secondary-color: #4CAF50;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
        }
        
        .edit-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .edit-header {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(106, 90, 205, 0.25);
        }
        
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.15em;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-label {
            margin-left: 0.5rem;
            color: #495057;
        }
        
        .btn-save {
            background-color: var(--primary-color);
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-save:hover {
            background-color: #5a4cb3;
            transform: translateY(-2px);
        }
        
        .btn-back {
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background-color: #f0f0f0;
            color: #5a4cb3;
        }
        
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    
    <div class="container">
        <div class="edit-container">
            <h2 class="edit-header mb-4"><i class="fas fa-edit me-2"></i>Edit Option</h2>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <div class="mb-4">
                    <label for="option_text" class="form-label">Option Text</label>
                    <input type="text" class="form-control" id="option_text" name="option_text" 
                           value="<?php echo htmlspecialchars($row['option_text']); ?>" required>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="is_answer" name="is_answer" value="1"
                        <?php echo $row['is_answer'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_answer">
                        Mark as correct answer
                    </label>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="my_options.php?id=<?php echo $quest_id; ?>" class="btn btn-back">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    <button type="submit" name="submit" class="btn btn-save text-white">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>