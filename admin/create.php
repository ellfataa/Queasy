<?php
    session_start();
    require_once("../layout/functions.php");
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
    :root 
        {
            --primary: #6a5acd;
            --secondary: #4CAF50;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-header .logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        
        .sidebar-header h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            transition: opacity 0.3s;
        }
        
        .sidebar.collapsed .sidebar-header h4,
        .sidebar.collapsed .sidebar-header .user-info {
            opacity: 0;
            transform: scale(0);
        }
        
        .user-panel {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
            text-align: center;
        }
        
        .user-panel .user-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .user-panel .user-role {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .nav-menu {
            padding: 20px 0;
        }
        
        .nav-item {
            margin-bottom: 8px;
            padding: 0 15px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            font-weight: 500;
        }
        
        .nav-link i {
            min-width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        .sidebar.collapsed .nav-link span {
            opacity: 0;
            transform: scale(0);
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
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../img/Q!.png" alt="Queasy Logo" class="logo">
            <h4>Queasy Admin</h4>
        </div>
        
        <div class="user-panel">
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            <div class="user-role">Administrator</div>
        </div>
        
        <nav class="nav-menu">
            <div class="nav-item">
                <a href="?content=dashboard" class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=user" class="nav-link <?= $currentPage == 'user' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=category" class="nav-link <?= $currentPage == 'category' ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=quiz" class="nav-link <?= $currentPage == 'quiz' ? 'active' : '' ?>">
                    <i class="fas fa-gamepad"></i>
                    <span>Quizzes</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=questions" class="nav-link <?= $currentPage == 'questions' ? 'active' : '' ?>">
                    <i class="fas fa-question-circle"></i>
                    <span>Questions</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=options" class="nav-link <?= $currentPage == 'options' ? 'active' : '' ?>">
                    <i class="fas fa-list-ul"></i>
                    <span>Options</span>
                </a>
            </div>
            <div class="nav-item" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                <a href="../logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </div>

    <!--form submit untuk quiz-->
    <?php if($table == "quizzes") : ?>
        <div class="create-card shadow">
        <h2 class="text-center create-header fw-semibold mt-4 mb-3">Create Quiz</h2>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Title</td>
                    <td>:</td>
                    <td><input type="text" name="title" required></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>:</td>
                    <td><textarea name="desc" cols="30" rows="4" required></textarea></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>:</td>
                    <td><select name="category" id="category" required>
                        <?php
                            while($category = mysqli_fetch_assoc($category_query)){
                                echo "<option value='".$category["id"]."'";
                                    if (isset($_GET["categ_id"]) && $category["id"] == $_GET["categ_id"]){
                                        echo " selected";
                                    }
                                echo ">".$category["category_name"]."</option>";
                            }
                        ?>
                    </select></td>
                </tr>
                <tr>
                    <td>Creator</td>
                    <td>:</td>
                    <td><select name="creator" id="creator" required>
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

    <!-- form submit untuk questions -->
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
                                if(isset($_GET["quiz_id"]) && $quiz["id"] == $_GET["quiz_id"]){
                                    echo " selected";
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
                    <textarea name="wrong_story" cols="30" rows="4" placeholder="Story to show when answer is wrong" required></textarea>
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

    <!-- form submit untuk options -->
    <?php if($table == "options") : ?>
        <div class="create-card shadow">
        <h2 class="text-center create-header  fw-semibold mt-4 mb-3">Create Option</h2>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Option text</td>
                    <td>:</td>
                    <td><input type="text" name="option_text" required></td>
                </tr>
                <tr>
                    <td>Is answer</td>
                    <td>:</td>
                    <td><input type="checkbox" name="is_answer" value="1">Correct</td>
                </tr>
                <tr>
                    <td>Question</td>
                    <td>:</td>
                    <td><select name="question" id="question" required>
                        <?php
                            while($question = mysqli_fetch_assoc($question_query)){
                                echo "<option value='".$question["id"]."'";
                                    if(isset($_GET["question_id"]) && $question["id"] == $_GET["question_id"]){
                                        echo " selected";
                                    }
                                echo ">".htmlspecialchars($question["quest_text"])."</option>";
                            }
                        ?>
                    </select></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><button type="submit" name="submit">Create</button></td>
                </tr>
            </table>
        </form>
    </div>
    <?php endif;?>

    <!-- form submit untuk user -->
    <?php if($table == "user") : ?>
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
    <?php endif; ?>

    <!-- form submit untuk category -->
    <?php if($table == "category") : ?>
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
    <?php endif; ?>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const searchInput = document.getElementById('searchInput');
            
            // Sidebar toggle functionality
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                }
            });
            
            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
            
            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const tables = document.querySelectorAll('table tbody tr');
                    
                    tables.forEach(function(row) {
                        const text = row.textContent.toLowerCase();
                        if (text.indexOf(value) > -1) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                }
            });
            
            // Smooth scrolling for sidebar links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Add loading state if needed
                    if (this.href && !this.href.includes('#')) {
                        const icon = this.querySelector('i');
                        if (icon && !icon.classList.contains('fa-sign-out-alt')) {
                            const originalClass = icon.className;
                            icon.className = 'fas fa-spinner fa-spin';
                            
                            // Restore original icon after a delay
                            setTimeout(() => {
                                icon.className = originalClass;
                            }, 1000);
                        }
                    }
                });
            });
            
            // Auto-hide mobile sidebar after navigation
            if (window.innerWidth <= 768) {
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        setTimeout(() => {
                            sidebar.classList.remove('show');
                        }, 300);
                    });
                });
            }
        });
    </script>

</body>
</html>

<?php
// Processing form submissions
if (isset($_POST["submit"])){
    if ($table == "quizzes"){
        $title = mysqli_real_escape_string($mysqli, $_POST["title"]);
        $desc = mysqli_real_escape_string($mysqli, $_POST["desc"]);
        $category = mysqli_real_escape_string($mysqli, $_POST["category"]);
        $creator = mysqli_real_escape_string($mysqli, $_POST["creator"]);
        
        $result = mysqli_query($mysqli, "INSERT INTO quizzes(title, description, category_id, creator_id) VALUES('$title', '$desc', '$category', '$creator')");
        
        if($result){
            echo "<script>
                    alert('Quiz berhasil dibuat');
                    window.location.href='index.php?content=quiz" . (isset($_GET["categ_id"]) ? "&categ_id=".$_GET["categ_id"]."&name=".$_GET["categ_name"] : "") . "';
                </script>";
        }else{
            echo "<script>
                    alert('Quiz gagal dibuat: " . mysqli_error($mysqli) . "');
                </script>";
        }
    } 
    elseif ($table == "questions"){
        $quest_text = mysqli_real_escape_string($mysqli, $_POST["quest_text"]);
        $quiz = mysqli_real_escape_string($mysqli, $_POST["quiz"]);
        
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
            
            // PERBAIKAN: Redirect ke halaman Questions Management di admin panel
            $redirect_url = "index.php?content=questions";
            
            // Jika ada quiz_id dan quiz_name dari parameter GET, tambahkan ke URL
            if (isset($_GET["quiz_id"]) && $_GET["quiz_id"] !== "") {
                $redirect_url .= "&quiz_id=" . $_GET["quiz_id"];
            }
            if (isset($_GET["quiz_name"]) && $_GET["quiz_name"] !== "") {
                $redirect_url .= "&quiz_name=" . urlencode($_GET["quiz_name"]);
            }
            
            echo "<script>
                    alert('Question and story segments berhasil dibuat');
                    window.location.href='$redirect_url';
                </script>";
        }else{
            echo "<script>
                    alert('Question gagal dibuat: " . mysqli_error($mysqli) . "');
                </script>";
        }
    } 
    elseif ($table == "options"){
        $option_text = mysqli_real_escape_string($mysqli, $_POST["option_text"]);
        $is_answer = isset($_POST["is_answer"]) ? $_POST["is_answer"] : 0;
        $question = mysqli_real_escape_string($mysqli, $_POST["question"]);
        
        $result = mysqli_query($mysqli, "INSERT INTO options(option_text, is_answer, question_id) VALUES('$option_text', '$is_answer', '$question')");
        
        if($result){
            // PERBAIKAN: Redirect ke halaman Options Management di admin panel
            $redirect_url = "index.php?content=options";
            
            // Jika ada question_id dan question_text dari parameter GET, tambahkan ke URL
            if (isset($_GET["question_id"]) && $_GET["question_id"] !== "") {
                $redirect_url .= "&question_id=" . $_GET["question_id"];
            }
            if (isset($_GET["question_text"]) && $_GET["question_text"] !== "") {
                $redirect_url .= "&question_text=" . urlencode($_GET["question_text"]);
            }
            
            echo "<script>
                    alert('Option berhasil dibuat');
                    window.location.href='$redirect_url';
                </script>";
        }else{
            echo "<script>
                    alert('Option gagal dibuat: " . mysqli_error($mysqli) . "');
                </script>";
        }
    }
    elseif ($table == "user") {
        $username = mysqli_real_escape_string($mysqli, $_POST["username"]);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($mysqli, $_POST["email"]);
        $is_admin = isset($_POST["is_admin"]) ? 1 : 0;
        
        $result = mysqli_query($mysqli, "INSERT INTO user(username, password, email, role) VALUES('$username', '$password', '$email', '$is_admin')");
        
        if ($result) {
            echo "<script>
                    alert('User berhasil dibuat');
                    window.location.href='index.php?content=user';
                </script>";
        } else {
            echo "<script>
                    alert('User gagal dibuat: " . mysqli_error($mysqli) . "');
                </script>";
        }
    }
    elseif ($table == "category") {
        $category_name = mysqli_real_escape_string($mysqli, $_POST["category_name"]);
        
        $result = mysqli_query($mysqli, "INSERT INTO category(category_name) VALUES('$category_name')");
        
        if ($result) {
            echo "<script>
                    alert('Category berhasil dibuat');
                    window.location.href='index.php?content=category';
                </script>";
        } else {
            echo "<script>
                    alert('Category gagal dibuat: " . mysqli_error($mysqli) . "');
                </script>";
        }
    }
}
?>