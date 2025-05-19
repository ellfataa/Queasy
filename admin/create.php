<?php
    session_start();
    require_once("../functions.php");
    if(!isset($_SESSION["login"])){
        header("Location: ../login.php");
        exit;
    }
    if(!isset($_SESSION["admin"])){
        header("Location: ../index.php");
        exit;
    }

    $table = $_GET["table"];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"
      integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv"
      crossorigin="anonymous"
    />
    <style>

        :root {
            --primary: #6a5acd;
            --secondary: #4CAF50;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
        }
        
        .create-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 700px;
            margin: 2rem auto;
            border: none;
        }
        
        .create-header {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.8rem;
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
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(106, 90, 205, 0.25);
        }
        
        textarea.form-control {
            min-height: 120px;
        }
        
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }
        
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(106, 90, 205, 0.25);
        }
        
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.15em;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-label {
            margin-left: 0.5rem;
        }
        
        .btn-create {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s;
            display: block;
            margin: 1.5rem auto 0;
        }
        
        .btn-create:hover {
            background-color: #5a4cb3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 90, 205, 0.3);
        }
        
        .btn-back {
            display: inline-block;
            margin-top: 1rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            color: #5a4cb3;
            text-decoration: underline;
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

        input[type="text"], input[type="password"], input[type="email"],
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
        }

        button[type="submit"]:hover {
            background-color: #cea51c;
        }
    </style>
    <title>Create</title>
</head>
<body>
    <!--form submit untuk quiz-->
    <?php if($table == "quizzes") : ?>
        <div class="create-card shadow">
        <h2 class="text-center create-header fw-semibold mt-4 mb-3">Create Quiz</h2>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Title</td>
                    <td>:</td>
                    <td><input type="text" name="title"></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>:</td>
                    <td><textarea name="desc" cols="30" rows="4"></textarea></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>:</td>
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
                    <td>Creator</td>
                    <td>:</td>
                    <td><select name="creator" id="creator">
                        <?php
                            while($creator = mysqli_fetch_assoc($user_query)){
                                echo "<option value='".$creator["id"]."'>".$creator["username"]."</option>";
                            }
                        ?>
                    </select></td>
                </tr>    
                <tr>
                    <td></td>
                    <td></td>
                    <td><button type="submit" name="submit" class='mb-3'>Create</button></td>
                </tr>
            </table>
        </form>
        </div>
    <?php endif; ?>

    <?php if($table == "questions") : ?>
    <div class="create-card shadow">
    <h2 class="text-center create-header fw-semibold mt-4 mb-3">Create Question</h2>
    <form action="" method="post">
        <table>
            <tr>
                <td>Question text</td>
                <td>:</td>
                <td><textarea name="quest_text" cols="30" rows="4" required placeholder="Enter your question here"></textarea></td>
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
                    <textarea name="correct_story" cols="30" rows="4" placeholder="Story to show when answer is correct" required></textarea>
                </td>
            </tr>
            
            <!-- Story Segment for Wrong Answer -->
            <tr>
                <td>Wrong Answer Story</td>
                <td>:</td>
                <td>
                    <textarea name="wrong_story" cols="30" rows="4" placeholder="Story to show when answer is correct" required></textarea>
                </td>
            </tr>
            
            <tr>
                <td></td>
                <td></td>
                <td><button type="submit" name="submit" class="btn-create">Create</button></td>
            </tr>
        </table>
    </form>
    </div>
<?php endif; ?>
    <?php if($table == "options") : ?>
        <div class="create-card shadow">
        <h2 class="text-center create-header  fw-semibold mt-4 mb-3">Create Option</h2>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Option text</td>
                    <td>:</td>
                    <td><input type="text" name="option_text"></td>
                </tr>
                <tr>
                    <td>Is answer</td>
                    <td>:</td>
                    <td><input type="checkbox" name="is_answer" value="1">Correct</td>
                </tr>
                <tr>
                    <td>Question</td>
                    <td>:</td>
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
                    <td></td>
                    <td></td>
                    <td><button type="submit" name="submit">Create</button></td>
                </tr>
            </table>
        </form>
    <?php endif;?>
    </div>
</body>
</html>

<?php
    if (isset($_POST["submit"])){
        if ($table == "quizzes"){
            $title = $_POST["title"];
            $desc = $_POST["desc"];
            $category = $_POST["category"];
            $creator = $_POST["creator"];
            $result = mysqli_query($mysqli, "INSERT INTO quizzes(title, description, category_id, creator_id) VALUES('$title', '$desc', '$category', '$creator')");
            if($result){
                echo "<script>
                        alert('Quiz berhasil dibuat');
                        window.location.href='index.php?content=quiz&categ_id=".$_GET["categ_id"]."&name=".$_GET["categ_name"]."';
                    </script>";
            }else{
                echo "<script>
                        alert('Quiz gagal dibuat');
                    </script>";
            }
       } else if (isset($_POST["submit"])){
        if ($table == "questions"){
            $quest_text = $_POST["quest_text"];
            $quiz = $_POST["quiz"];
            
            // Insert question
            $result = mysqli_query($mysqli, "INSERT INTO questions(quest_text, quiz_id) VALUES('$quest_text', '$quiz')");
            
            if($result){
                $question_id = mysqli_insert_id($mysqli);
                
                // Insert correct answer story segment
                $correct_story = mysqli_real_escape_string($mysqli, $_POST["correct_story"]);
                mysqli_query($mysqli, 
                    "INSERT INTO story_segments(story_text, show_on_correct, show_on_wrong, quiz_id, question_id) 
                     VALUES('$correct_story', 1, 0, '$quiz', '$question_id')");
                
                // Insert wrong answer story segment
                $wrong_story = mysqli_real_escape_string($mysqli, $_POST["wrong_story"]);
                mysqli_query($mysqli, 
                    "INSERT INTO story_segments(story_text, show_on_correct, show_on_wrong, quiz_id, question_id) 
                     VALUES('$wrong_story', 0, 1, '$quiz', '$question_id')");
                
                echo "<script>
                        alert('Question and story segments berhasil dibuat');
                    </script>";
                header("Location: view_question.php?quiz_id=".$_GET["quiz_id"]."&quiz_name=".$_GET["quiz_name"]."");
            }else{
                echo "<script>
                        alert('Question gagal dibuat');
                    </script>";
            }
        }
       } else if ($table == "options"){
            $option_text = $_POST["option_text"];
            if ($_POST["is_answer"]){
                $is_answer = $_POST["is_answer"];
            } else {
                $is_answer = 0;
            }
            $question = $_POST["question"];
            $result = mysqli_query($mysqli, "INSERT INTO options(option_text, is_answer, question_id) VALUES('$option_text', '$is_answer', '$question')");
            if($result){
                echo "<script>
                        alert('Option berhasil dibuat');
                    </script>";
                header("Location: view_option.php?question_id=".$_GET["quest_id"]."&question_text=".$_GET["quest_text"]."");
            }else{
                echo "<script>
                        alert('Option gagal dibuat');
                    </script>";
            }
       }
}

if ($table == "user") {
    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $email = $_POST["email"];
        $is_admin = isset($_POST["is_admin"]) ? 1 : 0;
        $result = mysqli_query($mysqli, "INSERT INTO user(username, password, email, role) VALUES('$username', '$password', '$email', '$is_admin')");
        if ($result) {
            echo "<script>
                    alert('User berhasil dibuat');
                    window.location.href='index.php?content=user';
                </script>";
        } else {
            echo "<script>
                    alert('User gagal dibuat');
                </script>";
        }
    }
?>
    <div class="card shadow">
        <h2 class="text-center create-header fw-semibold mt-4 mb-3">Create User</h2>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Username</td>
                    <td>:</td>
                    <td><input type="text" name="username" required></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td>:</td>
                    <td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><input type="email" name="email" required></td>
                </tr>
                <tr>
                    <td>Is Admin</td>
                    <td>:</td>
                    <td><input type="checkbox" name="is_admin" value="1">Yes</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><button type="submit" name="submit" class='mb-3'>Create</button></td>
                </tr>
            </table>
        </form>
    </div>
<?php
}

if ($table == "category") {
    if (isset($_POST["submit"])) {
        $category_name = $_POST["category_name"];
        $result = mysqli_query($mysqli, "INSERT INTO category(category_name) VALUES('$category_name')");
        if ($result) {
            echo "<script>
                    alert('Category berhasil dibuat');
                    window.location.href='index.php?content=category';
                </script>";
        } else {
            echo "<script>
                    alert('Category gagal dibuat');
                </script>";
        }
    }
?>
    <div class="card shadow">
        <h2 class="text-center create-header fw-semibold mt-4 mb-3">Create Category</h2>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Category Name</td>
                    <td>:</td>
                    <td><input type="text" name="category_name" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><button type="submit" name="submit" class='mb-3'>Create</button></td>
                </tr>
            </table>
        </form>
    </div>
<?php
}
?>