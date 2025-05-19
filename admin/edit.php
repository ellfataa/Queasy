<?php
    session_start();
    if(!isset($_SESSION["login"])){
        header("Location: ../login.php");
        exit;
    }
    if(!isset($_SESSION["admin"])){
        header("Location: ../index.php");
        exit;
    }

    require '../config.php'; // pastikan file koneksi database sudah di-include

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"
      integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv"
      crossorigin="anonymous"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f1f1;
        }

        h1 {
            text-align: center;
            margin: 3rem 0;
            font-weight: bold;
            color: #2c2a3b;
        }

        table {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 10px;
        }

        .card{
            width: 95%;
            margin: 0 auto 3rem;
        }

        td {
            padding: 10px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #2c2a3b;
            resize: none;
        }

        select {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #2c2a3b;
        }

        button[type="submit"] {
            padding: 9px;
            background-color: #fcc822;
            color: #2c2a3b;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 60px;
            margin-bottom: 40px;
        }

        button[type="submit"]:hover {
            background-color: #cea51c;
        }
    </style>
    <title>Edit</title>
</head>
<body>
    <!--form edit untuk user-->
    
    <?php if($table == "user") : ?>
        <div class="card">
        <h1>Edit User</h1>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" value="<?php echo $edit_data["username"]; ?>"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email" value="<?php echo $edit_data["email"]; ?>"></td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td>
                        <select name="role">
                            <option value="admin" <?php echo ($edit_data["role"] == "admin") ? "selected" : ""; ?>>Admin</option>
                            <option value="user" <?php echo ($edit_data["role"] == "user") ? "selected" : ""; ?>>User</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><button type="submit" name="submit">Edit</button></td>
                </tr>
            </table>
        </form>
        </div>
    <?php endif; ?>
    <!--form edit untuk quiz-->
    <?php if($table == "quizzes") : ?>
        <div class="card">
        <h1>Edit Quiz</h1>
        <br>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Title</td>
                    <td><input type="text" name="title" value="<?php echo $_GET["title"] ?>"></td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td><textarea name="desc" cols="30" rows="4"><?php echo $_GET["desc"]?></textarea></td>
                </tr>
                    <td>Kategori</td>
                    <td><select name="category" id="category">
                            <?php
                                while($category = mysqli_fetch_assoc($category_query)){
                                    echo "<option value='".$category["id"]."'";
                                        if ($category["id"] == $_GET["categ_id"]){
                                            echo "selected";
                                        }
                                    echo ">".$category["category_name"]."</option>";
                                }
                            ?>
                        </select></td>
                </tr>
                <tr>
                    <td>Pembuat</td>
                    <td><select name="creator" id="creator">
                        <?php
                            while($creator = mysqli_fetch_assoc($user_query)){
                                echo "<option value='".$creator["id"]."'>".$creator["username"]."</option>";
                            }
                        ?>
                    </select></td>
                </tr>  
                <tr>
                    <td><button type="submit" name="submit">Edit</button></td>
                </tr>
            </table>
        </form>
        </div>
    <?php endif; ?>
    <?php if($table == "question") : ?>
        <div class="card shadow">
        <h2 class="text-center create-header fw-semibold mt-4 mb-3">Edit Question</h2>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
            <table>
                <tr>
                    <td>Question text</td>
                    <td>:</td>
                    <td><textarea name="quest_text" cols="30" rows="4" required><?= htmlspecialchars($_GET['quest_text'] ?? '') ?></textarea></td>
                </tr>
                <tr>
                    <td>Quiz</td>
                    <td>:</td>
                    <td><select name="quiz" id="quiz" required>
                        <?php
                            mysqli_data_seek($quiz_query, 0);
                            while($quiz = mysqli_fetch_assoc($quiz_query)){
                                echo "<option value='".$quiz["id"]."'";
                                    if($quiz["id"] == $_GET["quiz_id"]){
                                        echo "selected";
                                    }
                                echo ">".htmlspecialchars($quiz["title"])."</option>";
                            }
                        ?>
                    </select></td>
                </tr>
                
                <!-- Story Segment for Correct Answer -->
                <tr>
                    <td>Correct Answer Story</td>
                    <td>:</td>
                    <td>
                        <?php
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
                        <textarea name="correct_story" cols="30" rows="4" required><?= htmlspecialchars($correct_story) ?></textarea>
                    </td>
                </tr>
                
                <!-- Story Segment for Wrong Answer -->
                <tr>
                    <td>Wrong Answer Story</td>
                    <td>:</td>
                    <td>
                        <textarea name="wrong_story" cols="30" rows="4" required><?= htmlspecialchars($wrong_story) ?></textarea>
                    </td>
                </tr>
                
                <tr>
                    <td></td>
                    <td></td>
                    <td><button type="submit" name="submit" class="btn-create w-25">Update</button></td>
                </tr>
            </table>
        </form>
        </div>
    <?php endif; ?>
    <?php if($table == "option") : ?>
        <div class="card">
        <h1>Edit Option</h1>
        <br>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Option text</td>
                    <td><input type="text" name="option_text" value="<?php echo $_GET["option_text"] ?>"></td>
                </tr>
                <tr>
                    <td>Is answer</td>
                    <td>
                        <select name="is_answer" id="is_answer">
                            <option value="1" <?php echo ($edit_data["is_answer"] == 1) ? "selected" : ""; ?>>True</option>
                            <option value="0" <?php echo ($edit_data["is_answer"] == 0) ? "selected" : ""; ?>>False</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Question</td>
                    <td><select name="question" id="question">
                        <?php
                            while($question = mysqli_fetch_assoc($question_query)){
                                echo "<option value='".$question["id"]."'";
                                    if($question["id"] == $_GET["question_id"]){
                                        echo "selected";
                                    }
                                echo ">".$question["quest_text"]."</option>";
                            }
                        ?>
                </tr>
                <tr>
                    <td><button type="submit" name="submit">Edit</button></td>
                </tr>
            </table>
        </form>
        </div>
    <?php endif;?>
    <?php if ($table == "category") : ?>
        <div class="card">
        <h1>Edit Category</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Category Name</td>
                    <td><input type="text" name="category_name" value="<?php echo htmlspecialchars($edit_data["category_name"]); ?>" required></td>
                </tr>
                <tr>
                    <td>Image</td>
                    <td>
                        <?php if (!empty($edit_data["image"])) : ?>
                            <img src="../uploads/<?php echo htmlspecialchars($edit_data["image"]); ?>" alt="Category Image" />
                        <?php endif; ?>
                        <input type="file" name="image" accept="image/*" />
                    </td>
                </tr>
                <tr>
                    <td><button type="submit" name="submit">Edit</button></td>
                </tr>
            </table>
        </form>
        </div>
    <?php endif; ?>
</body>
</html>
<?php
    if(isset($_POST["submit"])){
    echo "submit terpost";
    if($table == "user"){
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
        }
        } else if($table == "quizzes"){
            $title = $_POST["title"];
            $desc = $_POST["desc"];
            $query = "UPDATE quizzes SET title = '$title', description = '$desc' WHERE id = '$id'";
            $result = mysqli_query($mysqli,$query);
            if($result){
                echo "<script>
                    alert('Data berhasil diubah');
                    document.location.href = 'index.php?content=quiz&categ_id=".$_GET["categ_id"]."&name=".$_GET["categ_name"]."';
                </script>";
            }
        } else if($table == "option"){
            $option_text = $_POST["option_text"];
            if ($_POST["is_answer"]){
                $is_answer = $_POST["is_answer"];
            } else {
                $is_answer = 0;
            }
            $query = "UPDATE options SET option_text = '$option_text', is_answer = '$is_answer' WHERE id = '$id'";
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
        }
    }

    if ($table == "category") {
        if (isset($_POST["submit"])) {
            $category_name = $_POST["category_name"];
            $query = "UPDATE category SET category_name = '$category_name' WHERE id = '$id'";
            $result = mysqli_query($mysqli, $query);
            if ($result) {
                echo "<script>
                    alert('Data berhasil diubah');
                    document.location.href = 'index.php?content=category';
                </script>";
            }
        }
    }
?>