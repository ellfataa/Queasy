<?php
// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Simpan username sebelum logout untuk ditampilkan di alert
$username = $_SESSION['username'] ?? 'User';

// Hapus semua variabel session
$_SESSION = [];

// Hapus session cookie jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Hancurkan session
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logout - Queasy</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fcc822 0%, #f9b700 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .logout-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .logout-container h2 {
            color: #18152d;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .spinner-border {
            color: #18152d;
        }
        
        .logout-text {
            color: #18152d;
            font-weight: 500;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h2>Queasy</h2>
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="logout-text">Logging out...</p>
    </div>

    <script>
        // Tampilkan alert sukses logout
        Swal.fire({
            title: 'Logout Berhasil!',
            text: 'Terima kasih <?= htmlspecialchars($username) ?>, sampai jumpa lagi!',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#fcc822',
            timer: 3000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            // Redirect ke halaman utama setelah alert
            window.location.href = 'index.php';
        });

        // Fallback redirect jika SweetAlert2 gagal load
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 3000);

        // Prevent back button
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>