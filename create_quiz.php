<?php
session_start();
require_once("config.php");
if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION["id"];

if(isset($_POST["submit"])) {
    $title = mysqli_real_escape_string($mysqli, $_POST["title"]);
    $desc = mysqli_real_escape_string($mysqli, $_POST["desc"]);
    $category = (int)$_POST["category"];
    
    $query = "INSERT INTO quizzes (title, description, category_id, creator_id) VALUES ('$title', '$desc', '$category', '$user_id')";
    if(mysqli_query($mysqli, $query)) {
        header("Location: create_questions.php?quiz_id=".$mysqli->insert_id);
        exit;
    } else {
        $error = "Failed to create quiz: " . mysqli_error($mysqli);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Quiz | Queasy</title>

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
        
        .creation-header {
            background: linear-gradient(135deg, var(--primary-color), #8a6de9);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            text-align: center;
        }
        
        .creation-title {
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
        
        .next-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
            display: block;
        }
        
        .next-btn:hover {
            background-color: #5a4cb3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 90, 205, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border-left: 5px solid #dc3545;
        }
        
        @media (max-width: 768px) {
            .creation-title {
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
    
    <div class="creation-header">
        <div class="container">
            <h1 class="creation-title">Create New Quiz</h1>
            <p class="lead">Start by filling out the basic information</p>
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
                    <label for="title" class="form-label">Quiz Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                
                <div class="mb-4">
                    <label for="desc" class="form-label">Description</label>
                    <textarea class="form-control" id="desc" name="desc" rows="4" required></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <?php
                        $category_query = mysqli_query($mysqli, "SELECT * FROM category");
                        while($category = mysqli_fetch_assoc($category_query)):
                            $selected = (isset($_GET["categ_id"]) && $category["id"] == $_GET["categ_id"]) ? "selected" : "";
                        ?>
                            <option value="<?php echo $category["id"]; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($category["category_name"]); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <button type="submit" name="submit" class="next-btn mt-4">
                    <i class="fas fa-arrow-right me-2"></i> Continue
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>