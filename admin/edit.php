<?php
session_start();

// Include required files
require_once(__DIR__ . "/../layout/functions.php");

// Check if user is logged in and is admin
if(!isset($_SESSION['username'])) {
    header('location:../login.php');
    exit;
}

if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('location:../index.php');
    exit;
}

// Check if required parameters are provided
if(!isset($_GET["id"]) || !isset($_GET["table"])) {
    header("Location: index.php");
    exit;
}

$id = $_GET["id"];
$table = $_GET["table"];

// Ambil data yang akan diedit
if ($table == "user") {
    $edit_data = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM user WHERE id = '$id'"));
} else if ($table == "quizzes") {
    $edit_data = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM quizzes WHERE id = '$id'"));
} else if ($table == "question") {
    $edit_data = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM questions WHERE id = '$id'"));
} else if ($table == "option") {
    $edit_data = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM options WHERE id = '$id'"));
} else if ($table == "category") {
    $edit_data = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM category WHERE id = '$id'"));
}

$user_query = mysqli_query($mysqli, "SELECT * FROM user");
$category_query = mysqli_query($mysqli, "SELECT * FROM category");
$quiz_query = mysqli_query($mysqli, "SELECT * FROM quizzes");
$question_query = mysqli_query($mysqli, "SELECT * FROM questions");
$option_query = mysqli_query($mysqli, "SELECT * FROM options");

// Process form submission
if(isset($_POST["submit"])){
    if($table == "user"){
        $username = $_POST["username"];
        $email = $_POST["email"];
        $role = $_POST["role"];
        $query = "UPDATE user SET username = '$username', email = '$email', role = '$role' WHERE id = '$id'";
        $result = mysqli_query($mysqli, $query);
        if($result){
            echo "<script>
                alert('Data berhasil diubah');
                document.location.href = 'index.php?content=user';
            </script>";
        }
    } else if($table == "quizzes"){
        $title = $_POST["title"];
        $desc = $_POST["desc"];
        $category = $_POST["category"];
        $creator = $_POST["creator"];
        $query = "UPDATE quizzes SET title = '$title', description = '$desc', category_id = '$category', creator_id = '$creator' WHERE id = '$id'";
        $result = mysqli_query($mysqli,$query);
        if($result){
            echo "<script>
                alert('Data berhasil diubah');
                document.location.href = 'index.php?content=quiz&categ_id=".$_GET["categ_id"]."&name=".$_GET["categ_name"]."';
            </script>";
        }
    } else if($table == "option"){
        $option_text = $_POST["option_text"];
        if (isset($_POST["is_answer"])){
            $is_answer = $_POST["is_answer"];
        } else {
            $is_answer = 0;
        }
        $question = $_POST["question"];
        $query = "UPDATE options SET option_text = '$option_text', is_answer = '$is_answer', question_id = '$question' WHERE id = '$id'";
        $result = mysqli_query($mysqli,$query);
        if($result){
            echo "<script>
                alert('Data berhasil diubah');
                document.location.href = 'index.php?content=options&question_id=".$_GET["question_id"]."&quest_text=".$_GET["quest_text"]."';
            </script>";
        }
    } else if($table == "question"){
        $id = $_POST['id'];
        $quest_text = mysqli_real_escape_string($mysqli, $_POST["quest_text"]);
        $quiz = $_POST["quiz"];
        $correct_story = mysqli_real_escape_string($mysqli, $_POST["correct_story"]);
        $wrong_story = mysqli_real_escape_string($mysqli, $_POST["wrong_story"]);
        
        // Update question
        $query = "UPDATE questions SET quest_text = '$quest_text', quiz_id = '$quiz' WHERE id = '$id'";
        $result = mysqli_query($mysqli, $query);
        
        if($result){
            // Update or insert correct answer story segment
            $correct_query = mysqli_query($mysqli, 
                "SELECT id FROM story_segments 
                WHERE question_id = '$id' AND show_on_correct = 1");
            
            if(mysqli_num_rows($correct_query) > 0) {
                $correct_id = mysqli_fetch_assoc($correct_query)['id'];
                mysqli_query($mysqli, 
                    "UPDATE story_segments 
                    SET story_text = '$correct_story', quiz_id = '$quiz' 
                    WHERE id = '$correct_id'");
            } else {
                mysqli_query($mysqli, 
                    "INSERT INTO story_segments(story_text, show_on_correct, show_on_wrong, quiz_id, question_id) 
                    VALUES('$correct_story', 1, 0, '$quiz', '$id')");
            }
            
            // Update or insert wrong answer story segment
            $wrong_query = mysqli_query($mysqli, 
                "SELECT id FROM story_segments 
                WHERE question_id = '$id' AND show_on_wrong = 1");
            
            if(mysqli_num_rows($wrong_query) > 0) {
                $wrong_id = mysqli_fetch_assoc($wrong_query)['id'];
                mysqli_query($mysqli, 
                    "UPDATE story_segments 
                    SET story_text = '$wrong_story', quiz_id = '$quiz' 
                    WHERE id = '$wrong_id'");
            } else {
                mysqli_query($mysqli, 
                    "INSERT INTO story_segments(story_text, show_on_correct, show_on_wrong, quiz_id, question_id) 
                    VALUES('$wrong_story', 0, 1, '$quiz', '$id')");
            }
            
            echo "<script>
                    alert('Question and story segments berhasil diubah');
                    document.location.href = 'index.php?content=questions&quiz_id=".$_GET["quiz_id"]."&quiz_title=".$_GET["quiz_title"]."';
                </script>";
        } else {
            echo "<script>
                    alert('Error: ".mysqli_error($mysqli)."');
                </script>";
        }
    } else if ($table == "category") {
        $category_name = mysqli_real_escape_string($mysqli, $_POST["category_name"]);
        
        // Initialize image name with current image
        $image_name = $edit_data["img"]; // Keep existing image by default
        
        // Handle image upload
        if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            // Define upload directory
            $target_dir = "../img/"; // Changed from ../uploads/ to ../img/ to match the display path
            
            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Get file extension
            $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            
            // Generate unique filename
            $image_name = time() . "_" . uniqid() . "." . $imageFileType;
            $target_file = $target_dir . $image_name;
            
            // Check if image file is actually an image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
                // Check file size (limit to 5MB)
                if ($_FILES["image"]["size"] <= 5000000) {
                    // Allow certain file formats
                    if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                        // Attempt to move the uploaded file
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            // Delete old image if it exists and is not the default
                            if(!empty($edit_data["img"]) && $edit_data["img"] != 'default-category.png' && file_exists($target_dir . $edit_data["img"])) {
                                unlink($target_dir . $edit_data["img"]);
                            }
                            // File uploaded successfully, $image_name is already set
                        } else {
                            // Upload failed, keep old image
                            $image_name = $edit_data["img"];
                            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                        }
                    } else {
                        // Invalid file type, keep old image
                        $image_name = $edit_data["img"];
                        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
                    }
                } else {
                    // File too large, keep old image
                    $image_name = $edit_data["img"];
                    echo "<script>alert('Sorry, your file is too large. Maximum size is 5MB.');</script>";
                }
            } else {
                // Not a valid image, keep old image
                $image_name = $edit_data["img"];
                echo "<script>alert('File is not an image.');</script>";
            }
        }
        
        // Update database with escaped values
        $query = "UPDATE category SET category_name = '$category_name', img = '$image_name' WHERE id = '$id'";
        $result = mysqli_query($mysqli, $query);
        
        if ($result) {
            echo "<script>
                alert('Data berhasil diubah');
                document.location.href = 'index.php?content=category';
            </script>";
        } else {
            echo "<script>
                alert('Error updating category: " . mysqli_error($mysqli) . "');
            </script>";
        }
    }
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!--form edit untuk user-->
            <?php if($table == "user") : ?>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit User</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" value="<?php echo htmlspecialchars($edit_data["username"]); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($edit_data["email"]); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="admin" <?php echo ($edit_data["role"] == "admin") ? "selected" : ""; ?>>Admin</option>
                                    <option value="user" <?php echo ($edit_data["role"] == "user") ? "selected" : ""; ?>>User</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?content=user" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-primary">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!--form edit untuk quiz-->
            <?php if($table == "quizzes") : ?>
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-gamepad me-2"></i>Edit Quiz</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($_GET["title"] ?? $edit_data["title"]); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Description</label>
                                <textarea name="desc" id="desc" class="form-control" rows="4" required><?php echo htmlspecialchars($_GET["desc"] ?? $edit_data["description"]); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select" required>
                                    <?php
                                        mysqli_data_seek($category_query, 0);
                                        while($category = mysqli_fetch_assoc($category_query)){
                                            echo "<option value='".$category["id"]."'";
                                                if ($category["id"] == ($_GET["categ_id"] ?? $edit_data["category_id"])){
                                                    echo " selected";
                                                }
                                            echo ">".htmlspecialchars($category["category_name"])."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="creator" class="form-label">Creator</label>
                                <select name="creator" id="creator" class="form-select" required>
                                    <?php
                                        mysqli_data_seek($user_query, 0);
                                        while($creator = mysqli_fetch_assoc($user_query)){
                                            echo "<option value='".$creator["id"]."'";
                                                if($creator["id"] == $edit_data["creator_id"]){
                                                    echo " selected";
                                                }
                                            echo ">".htmlspecialchars($creator["username"])."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?content=quiz" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-success">Update Quiz</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!--form edit untuk question-->
            <?php if($table == "question") : ?>
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-question-circle me-2"></i>Edit Question</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                            <div class="mb-3">
                                <label for="quest_text" class="form-label">Question Text</label>
                                <textarea name="quest_text" id="quest_text" class="form-control" rows="4" required><?= htmlspecialchars($_GET['quest_text'] ?? $edit_data['quest_text']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="quiz" class="form-label">Quiz</label>
                                <select name="quiz" id="quiz" class="form-select" required>
                                    <?php
                                        mysqli_data_seek($quiz_query, 0);
                                        while($quiz = mysqli_fetch_assoc($quiz_query)){
                                            echo "<option value='".$quiz["id"]."'";
                                                if($quiz["id"] == ($_GET["quiz_id"] ?? $edit_data["quiz_id"])){
                                                    echo " selected";
                                                }
                                            echo ">".htmlspecialchars($quiz["title"])."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            
                            <?php
                            // Get existing story segments
                            $correct_story = '';
                            $wrong_story = '';
                            $story_query = mysqli_query($mysqli, 
                                "SELECT * FROM story_segments 
                                WHERE question_id = ".$_GET['id']);
                            while($story = mysqli_fetch_assoc($story_query)) {
                                if($story['show_on_correct']) {
                                    $correct_story = $story['story_text'];
                                } else {
                                    $wrong_story = $story['story_text'];
                                }
                            }
                            ?>
                            
                            <div class="mb-3">
                                <label for="correct_story" class="form-label">Correct Answer Story</label>
                                <textarea name="correct_story" id="correct_story" class="form-control" rows="4" required><?= htmlspecialchars($correct_story) ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="wrong_story" class="form-label">Wrong Answer Story</label>
                                <textarea name="wrong_story" id="wrong_story" class="form-control" rows="4" required><?= htmlspecialchars($wrong_story) ?></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?content=questions" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-info">Update Question</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!--form edit untuk option-->
            <?php if($table == "option") : ?>
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-list-ul me-2"></i>Edit Option</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="option_text" class="form-label">Option Text</label>
                                <input type="text" class="form-control" name="option_text" id="option_text" value="<?php echo htmlspecialchars($_GET["option_text"] ?? $edit_data["option_text"]); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="is_answer" class="form-label">Is Correct Answer</label>
                                <select name="is_answer" id="is_answer" class="form-select" required>
                                    <option value="1" <?php echo ($edit_data["is_answer"] == 1) ? "selected" : ""; ?>>True</option>
                                    <option value="0" <?php echo ($edit_data["is_answer"] == 0) ? "selected" : ""; ?>>False</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="question" class="form-label">Question</label>
                                <select name="question" id="question" class="form-select" required>
                                    <?php
                                        mysqli_data_seek($question_query, 0);
                                        while($question = mysqli_fetch_assoc($question_query)){
                                            echo "<option value='".$question["id"]."'";
                                                if($question["id"] == ($_GET["question_id"] ?? $edit_data["question_id"])){
                                                    echo " selected";
                                                }
                                            echo ">".htmlspecialchars($question["quest_text"])."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?content=options" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-warning">Update Option</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!--form edit untuk category-->
            <?php if ($table == "category") : ?>
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0"><i class="fas fa-tags me-2"></i>Edit Category</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="category_name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" name="category_name" id="category_name" value="<?php echo htmlspecialchars($edit_data["category_name"]); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <?php if (!empty($edit_data["img"])) : ?>
                                    <div class="mb-2">
                                        <img src="../img/<?php echo htmlspecialchars($edit_data["img"]); ?>" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                        <p class="text-muted small">Current image: <?php echo htmlspecialchars($edit_data["img"]); ?></p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                <div class="form-text">Leave empty to keep current image. Allowed formats: JPG, JPEG, PNG, GIF (Max 5MB)</div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?content=category" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-secondary">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>