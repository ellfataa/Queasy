<?php
    session_start();
    include("config.php");
    if(!isset($_SESSION["login"])){
        header("Location: login.php");
    }
    $id = $_GET["id"];
    $quest_id = $_GET["quest_id"];
    $result2 = mysqli_query($mysqli, "delete from user_answers where answer = $id");
    $result1 = mysqli_query($mysqli, "delete from options where id = $id");
    echo "<script>alert('Option deleted!');window.location='my_options.php?id=$quest_id';</script>";
?>