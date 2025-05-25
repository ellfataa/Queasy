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

    /**
     * Menghitung skor pengguna dalam satu kategori
     */
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
        
        if ($total_questions == 0) {
            return 0;
        }
        
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
        
        return ($correct_answers / $total_questions) * 100;
    }

    /**
     * Menghitung skor pengguna dalam satu kuis
     */
    function get_user_quiz_score($mysqli, $user_id, $quiz_id) {
        // Hitung total pertanyaan di kuis
        $total_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM questions WHERE quiz_id = $quiz_id");
        $total_data = mysqli_fetch_assoc($total_query);
        $total_questions = $total_data['total'];
        
        if ($total_questions == 0) {
            return 0;
        }
        
        // Hitung jawaban benar
        $correct_query = mysqli_query($mysqli, "
            SELECT SUM(ua.is_correct) as correct
            FROM user_answers ua
            JOIN questions q ON ua.question_id = q.id
            WHERE q.quiz_id = $quiz_id AND ua.user_id = $user_id
        ");
        $correct_data = mysqli_fetch_assoc($correct_query);
        $correct_answers = $correct_data['correct'] ?? 0;
        
        return ($correct_answers / $total_questions) * 100;
    }

    /**
     * Mengecek apakah pengguna sudah pernah mengerjakan kuis
     */
    function has_user_attempted_quiz($mysqli, $user_id, $quiz_id) {
        $query = mysqli_query($mysqli, "
            SELECT COUNT(*) as count
            FROM user_answers ua
            JOIN questions q ON ua.question_id = q.id
            WHERE q.quiz_id = $quiz_id AND ua.user_id = $user_id
        ");
        $data = mysqli_fetch_assoc($query);
        return $data['count'] > 0;
    }

    /**
     * Mengecek apakah kuis terkunci untuk pengguna
     * PERUBAHAN: Semua kuis di kategori selain Matematika (id=1) akan terkunci 
     * sampai kategori sebelumnya diselesaikan dengan skor >= 50%
     */
    function is_quiz_locked($mysqli, $user_id, $quiz_id, $category_id) {
        // Kategori Matematika (id=1) tidak pernah terkunci
        if ($category_id == 1) {
            return false;
        }

        // Untuk kategori lain, cek apakah kategori sebelumnya sudah diselesaikan
        $is_category_locked = is_category_locked($mysqli, $user_id, $category_id);
        
        // Jika kategori terkunci, maka semua kuis di dalamnya juga terkunci
        if ($is_category_locked) {
            return true;
        }

        // Jika kategori sudah terbuka, maka cek logika kuis internal
        // Ambil semua kuis dalam kategori, diurutkan berdasarkan ID
        $quizzes_query = mysqli_query($mysqli, "
            SELECT id FROM quizzes 
            WHERE category_id = $category_id 
            ORDER BY id ASC
        ");
        
        $quizzes = [];
        while ($quiz = mysqli_fetch_assoc($quizzes_query)) {
            $quizzes[] = $quiz['id'];
        }

        // Cari posisi kuis saat ini
        $current_quiz_index = array_search($quiz_id, $quizzes);
        
        // Kuis pertama dalam kategori yang sudah terbuka selalu bisa diakses
        if ($current_quiz_index === 0) {
            return false;
        }

        // Untuk kuis selanjutnya, cek apakah kuis sebelumnya sudah diselesaikan dengan skor >= 50%
        $previous_quiz_id = $quizzes[$current_quiz_index - 1];
        
        if (!has_user_attempted_quiz($mysqli, $user_id, $previous_quiz_id)) {
            return true; // Kuis sebelumnya belum dikerjakan
        }

        $previous_score = get_user_quiz_score($mysqli, $user_id, $previous_quiz_id);
        return $previous_score < 50; // Terkunci jika skor sebelumnya < 50%
    }

    /**
     * Mengecek apakah kategori terkunci untuk pengguna
     * PERUBAHAN: Kategori hanya terbuka jika kategori sebelumnya diselesaikan dengan skor >= 50%
     * Khusus kategori Matematika (id=1) selalu terbuka
     */
    function is_category_locked($mysqli, $user_id, $category_id) {
        // Kategori Matematika (id=1) selalu terbuka
        if ($category_id == 1) {
            return false;
        }

        // Untuk kategori lain, cek apakah kategori sebelumnya sudah diselesaikan dengan skor >= 50%
        $previous_category_id = $category_id - 1;
        
        // Pastikan kategori sebelumnya ada
        $prev_category_query = mysqli_query($mysqli, "SELECT id FROM category WHERE id = $previous_category_id");
        if (mysqli_num_rows($prev_category_query) == 0) {
            // Jika kategori sebelumnya tidak ada, anggap terbuka
            return false;
        }
        
        $previous_score = get_user_category_score($mysqli, $user_id, $previous_category_id);
        
        // Kategori terkunci jika skor kategori sebelumnya < 50%
        return $previous_score < 50;
    }

    /**
     * Mendapatkan ID kuis pertama dalam kategori
     */
    function get_first_quiz_id($mysqli, $category_id) {
        $query = mysqli_query($mysqli, "SELECT id FROM quizzes WHERE category_id = $category_id ORDER BY id ASC LIMIT 1");
        $result = mysqli_fetch_assoc($query);
        return $result ? $result['id'] : null;
    }

    /**
     * Menghitung progress pengguna dalam kategori (berapa % kuis yang sudah diselesaikan)
     */
    function get_category_progress($mysqli, $user_id, $category_id) {
        // Ambil semua kuis dalam kategori
        $total_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM quizzes WHERE category_id = $category_id");
        $total_data = mysqli_fetch_assoc($total_query);
        $total_quizzes = $total_data['total'];
        
        if ($total_quizzes == 0) {
            return 0;
        }

        // Hitung berapa kuis yang sudah dikerjakan
        $completed_query = mysqli_query($mysqli, "
            SELECT COUNT(DISTINCT q.quiz_id) as completed
            FROM user_answers ua
            JOIN questions q ON ua.question_id = q.id
            JOIN quizzes z ON q.quiz_id = z.id
            WHERE z.category_id = $category_id AND ua.user_id = $user_id
        ");
        $completed_data = mysqli_fetch_assoc($completed_query);
        $completed_quizzes = $completed_data['completed'];

        return ($completed_quizzes / $total_quizzes) * 100;
    }

    /**
     * Mendapatkan statistik lengkap pengguna
     */
    function get_user_stats($mysqli, $user_id) {
        $stats = [];
        
        // Total skor
        $user_query = mysqli_query($mysqli, "SELECT score FROM user WHERE id = $user_id");
        $user_data = mysqli_fetch_assoc($user_query);
        $stats['total_score'] = $user_data ? $user_data['score'] : 0;
        
        // Ranking
        $rank_query = mysqli_query($mysqli, "SELECT COUNT(*) + 1 as user_rank FROM user WHERE score > {$stats['total_score']}");
        $rank_data = mysqli_fetch_assoc($rank_query);
        $stats['rank'] = $rank_data['user_rank'];
        
        // Total kuis yang sudah dikerjakan
        $attempted_query = mysqli_query($mysqli, "
            SELECT COUNT(DISTINCT q.quiz_id) as attempted
            FROM user_answers ua
            JOIN questions q ON ua.question_id = q.id
            WHERE ua.user_id = $user_id
        ");
        $attempted_data = mysqli_fetch_assoc($attempted_query);
        $stats['quizzes_attempted'] = $attempted_data['attempted'];
        
        // Total kuis yang lulus (skor >= 50%)
        $passed_query = mysqli_query($mysqli, "
            SELECT COUNT(DISTINCT quiz_id) as passed
            FROM (
                SELECT q.quiz_id, 
                       SUM(ua.is_correct) / COUNT(*) * 100 as score
                FROM user_answers ua
                JOIN questions q ON ua.question_id = q.id
                WHERE ua.user_id = $user_id
                GROUP BY q.quiz_id
                HAVING score >= 50
            ) as passed_quizzes
        ");
        $passed_data = mysqli_fetch_assoc($passed_query);
        $stats['quizzes_passed'] = $passed_data['passed'];
        
        return $stats;
    }

?>