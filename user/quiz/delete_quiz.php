<?php
    session_start();
    include("config.php");
    if(!isset($_SESSION["login"])){
        header("Location: login.php");
    }
    $id = $_GET["id"];
    $quiz = mysqli_query($mysqli, "SELECT * FROM quizzes WHERE id = $id");
    $quest_del = mysqli_query($mysqli, "SELECT * FROM questions WHERE quiz_id = $id");
    while($quest = mysqli_fetch_array($quest_del)){
        $quest_id = $quest["id"];
        $del1 = mysqli_query($mysqli, "delete from user_answers where question_id = $quest_id");
        $del2 = mysqli_query($mysqli, "delete from options where question_id = $quest_id");
        $del3 = mysqli_query($mysqli, "DELETE FROM questions WHERE id = $quest_id");
    }
    $result3 = mysqli_query($mysqli, "DELETE FROM quizzes WHERE id = $id");
    echo "<script>alert('Quiz deleted!');window.location='my_quiz.php';</script>";
?>