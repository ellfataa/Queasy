<?php
session_start();
require_once(__DIR__ . "../../../layout/functions.php");
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
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">
    <link rel="stylesheet" href="create_quiz.css">
</head>
<body>
    <?php include("../../layout/navbar.php"); ?>
    
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