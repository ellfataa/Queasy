<?php
session_start();
if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}
require_once('functions.php');
$correct = 0;
$incorrect = [];
unset($_POST['submit']);
unset($_SESSION['score']);
unset($_SESSION['correct']);
unset($_SESSION['incorrect']);
unset($_SESSION['question_num']);
$answers = $_POST;
print_r($answers);
$question_num = count($answers);
$query_answer = mysqli_query($mysqli, "SELECT * FROM options WHERE is_answer = 1");
while($row_answer = mysqli_fetch_assoc($query_answer)){
    foreach ($answers as $question_id => $answer_id) {
        if ($question_id == $row_answer['question_id'] && $answer_id == $row_answer['id']) {
            $correct++;
        } else if ($question_id == $row_answer['question_id'] && $answer_id != $row_answer['id']) {
            $incorrect[$question_id] = $answer_id;
        }
    }
}
$score = $correct / $question_num * 100;
echo "<br>You answer $correct questions correctly";
echo "<br> You have answered $question_num questions";
echo "<br> Your score is $score";

$_SESSION['score'] = $score;
$_SESSION['correct'] = $correct;
$_SESSION['question_num'] = $question_num;
$_SESSION['incorrect'] = $incorrect;

$id = $_SESSION['id'];
$username = $_SESSION['username'];
echo $id;
echo $username;
foreach ($answers as $question_id => $option_id) {
    $option_query = mysqli_query($mysqli,"SELECT * FROM options WHERE id = '$option_id'");
    $option = mysqli_fetch_assoc($option_query);
    
    $insert_answer_query = mysqli_query($mysqli,"INSERT INTO user_answers (user_id, question_id, is_correct, answer) VALUES ('$id','$question_id','$option[is_answer]','$option_id') on duplicate key update answer = '$option_id'");
}
$user = mysqli_query($mysqli,"SELECT * FROM user WHERE id = '$id'");
$user_row = mysqli_fetch_assoc($user);
$answered = "select * from user_answers where user_id = '$user_row[id]' and is_correct = 1";
$answered_result = mysqli_query($mysqli,$answered);
$answered_row = mysqli_num_rows($answered_result);
$score = $answered_row * 10;
$upd_score = mysqli_query($mysqli,"update user set score = '$score' where id = '$user_row[id]'");
header("Location: hasil.php");
exit;
?>