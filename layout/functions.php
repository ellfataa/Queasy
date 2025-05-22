<?php
require_once("config.php");

define('BASE_URL', '/Queasy/');

function register($data){
    global $mysqli;
    $username = strtolower(stripslashes($data["username"]));
    $email = strtolower(stripslashes($data["email"]));
    $password = mysqli_real_escape_string($mysqli, $data["password"]);
    $password2 = mysqli_real_escape_string($mysqli, $data["password2"]);

    //pengecekan username
    $user = mysqli_query($mysqli, "SELECT username FROM user WHERE username = '$username'");
    if(mysqli_fetch_assoc($user)){
        echo "<script>
                alert('Username sudah terdaftar, gunakan username lain');
            </script>";
        return false;
    }
    //pengecekan password
    if($password !== $password2){
        echo "<script>
               alert('Password tidak sesuai');
            </script>";
        return false;
    }
    
    //enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    //memasukan data ke database
    mysqli_query($mysqli, "INSERT INTO user(username,email,password) VALUES('$username', '$email', '$password')");
    return mysqli_affected_rows($mysqli);
}
// Ganti fungsi get_user_category_score atau tambahkan ini:
function get_user_category_score($mysqli, $user_id, $category_id) {
    // Hitung total pertanyaan di kategori
    $total_query = mysqli_query($mysqli, "
        SELECT COUNT(*) as total 
        FROM questions q
        JOIN quizzes z ON q.quiz_id = z.id
        WHERE z.category_id = $category_id
    ");
    $total_data = mysqli_fetch_assoc($total_query);
    $total_questions = $total_data['total'];
    
    // Hitung jawaban benar
    $correct_query = mysqli_query($mysqli, "
        SELECT SUM(ua.is_correct) as correct
        FROM user_answers ua
        JOIN questions q ON ua.question_id = q.id
        JOIN quizzes z ON q.quiz_id = z.id
        WHERE z.category_id = $category_id AND ua.user_id = $user_id
    ");
    $correct_data = mysqli_fetch_assoc($correct_query);
    $correct_answers = $correct_data['correct'] ?? 0;
    
    return ($total_questions > 0) ? ($correct_answers / $total_questions) * 100 : 0;
}

?>