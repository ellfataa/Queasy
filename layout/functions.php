<?php
    require_once("config.php");

    function register($data, &$errors)
    {
        global $mysqli;

        $username = trim(strtolower(stripslashes($data["username"] ?? '')));
        $email    = trim(strtolower($data["email"] ?? ''));
        $password = $data["password"] ?? '';
        $password2 = $data["password2"] ?? '';

        // Validasi kosong
        if (empty($username) || empty($email) || empty($password) || empty($password2)) {
            $errors[] = "Semua field wajib diisi.";
            return 0;
        }

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email tidak valid.";
            return 0;
        }

        // Validasi panjang username
        if (strlen($username) < 3) {
            $errors[] = "Username minimal 3 karakter.";
            return 0;
        }

        // Validasi panjang password
        if (strlen($password) < 6) {
            $errors[] = "Password minimal 6 karakter.";
            return 0;
        }

        // Validasi password match
        if ($password !== $password2) {
            $errors[] = "Konfirmasi password tidak sesuai.";
            return 0;
        }

        // Cek username di DB
        $stmt = $mysqli->prepare("SELECT username FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Username sudah digunakan.";
            return 0;
        }

        // Enkripsi password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Simpan user
        $stmt = $mysqli->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash);
        $stmt->execute();

        return $stmt->affected_rows;
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