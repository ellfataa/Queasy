<?php
session_start();
require_once(__DIR__ . "../../../layout/functions.php");

// 1. Validate session and request
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error'] = "Invalid request method";
    header("Location: index.php");
    exit;
}

// 2. Validate and sanitize input data
$required_fields = ['quiz_id', 'question_id', 'answer'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
        $_SESSION['error'] = "Missing required field: $field";
        header("Location: index.php");
        exit;
    }
}

$quiz_id = (int)$_POST['quiz_id'];
$question_id = (int)$_POST['question_id'];
$answer_id = (int)$_POST['answer'];
$user_id = (int)$_SESSION['id'];

// Additional validation
if ($quiz_id <= 0 || $question_id <= 0 || $answer_id <= 0) {
    $_SESSION['error'] = "Invalid input data";
    header("Location: index.php");
    exit;
}

// 3. Verify question belongs to quiz
$question_check = mysqli_query($mysqli, 
    "SELECT 1 FROM questions WHERE id = $question_id AND quiz_id = $quiz_id LIMIT 1");

if (!$question_check || mysqli_num_rows($question_check) === 0) {
    $_SESSION['error'] = "Invalid question for this quiz";
    header("Location: index.php");
    exit;
}

// 4. Get correct answer (using prepared statement for security)
$stmt = $mysqli->prepare(
    "SELECT o.id, o.option_text 
     FROM options o 
     WHERE o.question_id = ? AND o.is_answer = 1 LIMIT 1");
$stmt->bind_param("i", $question_id);
$stmt->execute();
$correct_answer_result = $stmt->get_result();

if ($correct_answer_result->num_rows === 0) {
    $_SESSION['error'] = "No correct answer found for this question";
    header("Location: quiz.php?id=$quiz_id");
    exit;
}

$correct_answer = $correct_answer_result->fetch_assoc();
$stmt->close();

// 5. Determine if answer is correct
$is_correct = ($answer_id == $correct_answer['id']) ? 1 : 0;

// 6. Save user answer (using prepared statement)
$stmt = $mysqli->prepare("
    INSERT INTO user_answers (user_id, question_id, is_correct, answer)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        is_correct = VALUES(is_correct), 
        answer = VALUES(answer)
");
$stmt->bind_param("iiii", $user_id, $question_id, $is_correct, $answer_id);

if (!$stmt->execute()) {
    $_SESSION['error'] = "Failed to save your answer";
    header("Location: quiz.php?id=$quiz_id");
    exit;
}
$stmt->close();

// 7. Update session data
if (!isset($_SESSION['correct_answers'])) {
    $_SESSION['correct_answers'] = 0;
}

if ($is_correct) {
    $_SESSION['correct_answers']++;
    $_SESSION['answer_status'] = 'correct';
} else {
    if (!isset($_SESSION['lives'])) {
        $_SESSION['lives'] = 3;
    }
    $_SESSION['lives']--;
    $_SESSION['answer_status'] = 'wrong';
}

$_SESSION['last_correct_answer'] = htmlspecialchars($correct_answer['option_text']);

// 8. Track answered questions
if (!isset($_SESSION['questions_answered'])) {
    $_SESSION['questions_answered'] = [];
}
$_SESSION['questions_answered'][] = $question_id;

// 9. Move to next question
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 0;
}
$_SESSION['current_question']++;

// 10. Check if quiz is complete
$total_questions = count_questions($mysqli, $quiz_id);
$is_last_question = ($_SESSION['current_question'] >= $total_questions);

if ($_SESSION['lives'] <= 0 || $is_last_question) {
    // Calculate final score
    $_SESSION['score'] = ($total_questions > 0) 
        ? ($_SESSION['correct_answers'] / $total_questions) * 100 
        : 0;

    // Update user score
    $score_to_add = $_SESSION['correct_answers'] * 10;
    $stmt = $mysqli->prepare("UPDATE user SET score = score + ? WHERE id = ?");
    $stmt->bind_param("ii", $score_to_add, $user_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['quiz_finished'] = true;
}

// 11. Redirect back to quiz
header("Location: quiz.php?id=$quiz_id");
exit;

// Helper function to count questions
function count_questions($mysqli, $quiz_id) {
    $stmt = $mysqli->prepare("SELECT COUNT(*) as total FROM questions WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc();
    $stmt->close();
    return (int)$count['total'];
}
?>