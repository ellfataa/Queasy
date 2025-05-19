<?php
    session_start();
    include("config.php");
    if(!isset($_SESSION["login"])){
        header("Location: login.php");
    }
    $id = $_GET["id"];
    $quiz_id = $_GET["quiz_id"];
    $result2 = mysqli_query($mysqli, "delete from user_answers where question_id = $id");
    $result1 = mysqli_query($mysqli, "delete from options where question_id = $id");
    $result = mysqli_query($mysqli, "DELETE FROM questions WHERE id = $id");
    echo "<script>alert('Question deleted!');window.location='my_questions.php?id=$quiz_id';</script>";
?>