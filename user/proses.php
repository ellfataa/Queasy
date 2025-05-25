<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require_once('functions.php');

// Validasi input
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Metode akses tidak valid.";
    header("Location: dashboard.php");
    exit;
}

// Hapus session data lama
unset($_SESSION['score']);
unset($_SESSION['correct']);
unset($_SESSION['incorrect']);
unset($_SESSION['question_num']);

// Ambil jawaban user (hapus submit button dari array)
$answers = $_POST;
unset($answers['submit']);

if (empty($answers)) {
    $_SESSION['error'] = "Tidak ada jawaban yang dikirim.";
    header("Location: dashboard.php");
    exit;
}

$user_id = $_SESSION['id'];
$username = $_SESSION['username'];
$correct = 0;
$incorrect = [];
$question_num = count($answers);

try {
    // Mulai transaction untuk konsistensi data
    mysqli_autocommit($mysqli, FALSE);

    // Validasi bahwa semua question_id yang dikirim valid
    $question_ids = array_keys($answers);
    $question_ids_str = implode(',', array_map('intval', $question_ids));
    
    $validation_query = mysqli_query($mysqli, "
        SELECT COUNT(*) as count 
        FROM questions 
        WHERE id IN ($question_ids_str)
    ");
    $validation_data = mysqli_fetch_assoc($validation_query);
    
    if ($validation_data['count'] != $question_num) {
        throw new Exception("Data pertanyaan tidak valid.");
    }

    // Proses setiap jawaban
    foreach ($answers as $question_id => $option_id) {
        $question_id = intval($question_id);
        $option_id = intval($option_id);
        
        // Validasi bahwa option_id valid untuk question_id ini
        $option_query = mysqli_query($mysqli, "
            SELECT id, is_answer 
            FROM options 
            WHERE id = $option_id AND question_id = $question_id
        ");
        $option = mysqli_fetch_assoc($option_query);
        
        if (!$option) {
            throw new Exception("Jawaban tidak valid untuk pertanyaan ID: $question_id");
        }
        
        $is_correct = $option['is_answer'];
        
        if ($is_correct) {
            $correct++;
        } else {
            $incorrect[$question_id] = $option_id;
        }
        
        // Insert atau update jawaban user
        $insert_query = mysqli_query($mysqli, "
            INSERT INTO user_answers (user_id, question_id, is_correct, answer) 
            VALUES ($user_id, $question_id, $is_correct, $option_id)
            ON DUPLICATE KEY UPDATE 
                is_correct = $is_correct, 
                answer = $option_id,
                answered_at = CURRENT_TIMESTAMP
        ");
        
        if (!$insert_query) {
            throw new Exception("Gagal menyimpan jawaban untuk pertanyaan ID: $question_id");
        }
    }

    // Hitung skor quiz ini
    $quiz_score = ($question_num > 0) ? ($correct / $question_num) * 100 : 0;

    // Update total skor user (berdasarkan semua jawaban benar yang pernah dijawab)
    $total_correct_query = mysqli_query($mysqli, "
        SELECT COUNT(*) as total_correct 
        FROM user_answers 
        WHERE user_id = $user_id AND is_correct = 1
    ");
    $total_correct_data = mysqli_fetch_assoc($total_correct_query);
    $total_correct = $total_correct_data['total_correct'];
    
    // Update skor total user (setiap jawaban benar = 10 poin)
    $new_total_score = $total_correct * 10;
    $update_score_query = mysqli_query($mysqli, "
        UPDATE user 
        SET score = $new_total_score, 
            updated_at = CURRENT_TIMESTAMP 
        WHERE id = $user_id
    ");
    
    if (!$update_score_query) {
        throw new Exception("Gagal mengupdate skor total user.");
    }

    // Commit transaction
    mysqli_commit($mysqli);

    // Set session data untuk halaman hasil
    $_SESSION['score'] = $quiz_score;
    $_SESSION['correct'] = $correct;
    $_SESSION['question_num'] = $question_num;
    $_SESSION['incorrect'] = $incorrect;
    $_SESSION['total_score'] = $new_total_score;

    // Log untuk debugging (bisa dihapus di production)
    error_log("User $username (ID: $user_id) completed quiz - Score: $quiz_score%, Correct: $correct/$question_num, New Total Score: $new_total_score");

    header("Location: hasil.php");
    exit;

} catch (Exception $e) {
    // Rollback transaction jika ada error
    mysqli_rollback($mysqli);
    
    // Log error
    error_log("Quiz processing error for user $user_id: " . $e->getMessage());
    
    // Set error message dan redirect
    $_SESSION['error'] = "Terjadi kesalahan saat memproses jawaban: " . $e->getMessage();
    header("Location: dashboard.php");
    exit;
    
} finally {
    // Kembalikan autocommit
    mysqli_autocommit($mysqli, TRUE);
}
?>