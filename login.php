<?php
  session_start();
  require_once(__DIR__ . "/layout/functions.php");

  if (isset($_SESSION["login"])) {
      // Redirect berdasarkan role
      if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
          header("Location: admin/index.php");
      } else {
          header("Location: index.php");
      }
      exit;
  }

  $error = false;
  $success = '';
  $login_success = false;
  $redirect_url = 'index.php';

  if (isset($_GET["success"])) {
      $success = htmlspecialchars($_GET["success"]);
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $username = trim($_POST["username"] ?? '');
      $password = $_POST["password"] ?? '';

      // Validasi input kosong
      if (empty($username) || empty($password)) {
          $error = "Username dan password tidak boleh kosong.";
      } else {
          // Gunakan prepared statement untuk keamanan
          $stmt = $mysqli->prepare("SELECT * FROM user WHERE username = ?");
          $stmt->bind_param("s", $username);
          $stmt->execute();
          $user = $stmt->get_result();

          if ($user->num_rows === 1) {
              $row = $user->fetch_assoc();

              if (password_verify($password, $row["password"])) {
                  $_SESSION["login"] = true;
                  $_SESSION["username"] = $row["username"];
                  $_SESSION["id"] = $row["id"];
                  
                  if ($row["role"] === "admin") {
                      $_SESSION["admin"] = true;
                      $redirect_url = 'admin/index.php';
                  } else {
                      $redirect_url = 'index.php';
                  }

                  // Hitung skor dengan prepared statement
                  $score_stmt = $mysqli->prepare("SELECT COUNT(*) as correct_count FROM user_answers WHERE user_id = ? AND is_correct = 1");
                  $score_stmt->bind_param("i", $row['id']);
                  $score_stmt->execute();
                  $score_result = $score_stmt->get_result();
                  $score_data = $score_result->fetch_assoc();
                  $score = $score_data['correct_count'] * 10;
                  
                  // Update skor dengan prepared statement
                  $update_stmt = $mysqli->prepare("UPDATE user SET score = ? WHERE id = ?");
                  $update_stmt->bind_param("ii", $score, $row['id']);
                  $update_stmt->execute();

                  $login_success = true;
              } else {
                  $error = "Username atau password salah.";
              }
          } else {
              $error = "Username atau password salah.";
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Login - Queasy</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

  <!-- Custom CSS -->
  <style>
  :root {
    --primary-color: #fcc822;
    --secondary-color: #faf7f4;
    --text-primary: #333333;
    --text-secondary: #555555;
    --text-light: #777777;
    --white: #ffffff;
    --black: #000000;
    --border-color: #dddddd;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
  }

  body {
    background-color: var(--secondary-color);
    min-height: 100vh;
    font-size: 1rem; /* 16px */
    line-height: 1.5;
    color: var(--text-primary);
    display: flex;
    flex-direction: column;
  }

  /* Layout Utama */
  .login-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem; /* 20px */
  }

  .login-box {
    width: 100%;
    max-width: 62.5rem; /* 1000px */
    border-radius: 0.9375rem; /* 15px */
    overflow: hidden;
    box-shadow: var(--shadow);
    display: flex;
    background-color: var(--white);
  }

  /* Bagian Kiri (Gambar) */
  .left-section {
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
    background-color: var(--secondary-color);
    padding: 2.5rem; /* 40px */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .left-section img {
    max-width: 100%;
    height: auto;
    border-radius: 0.625rem; /* 10px */
    object-fit: cover;
  }

  /* Bagian Kanan (Form) */
  .right-section {
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
    background-color: var(--primary-color);
    padding: 2.5rem; /* 40px */
  }

  /* Header */
  .login-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.875rem; /* 30px */
  }

  .login-header h1 {
    font-size: 2rem; /* 32px */
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    line-height: 1.2;
  }

  .login-header h1 span {
    color: var(--white);
  }

  .back-btn {
    display: inline-flex;
    align-items: center;
    font-size: 0.875rem; /* 14px */
    color: var(--text-primary);
    text-decoration: none;
    transition: var(--transition);
    padding: 0.3125rem 0.625rem; /* 5px 10px */
    border-radius: 0.3125rem; /* 5px */
  }

  .back-btn:hover {
    background-color: rgba(0, 0, 0, 0.05);
  }

  .back-btn i {
    margin-right: 0.3125rem; /* 5px */
  }

  /* Pesan Selamat Datang */
  .welcome-message {
    margin-bottom: 1.5rem; /* 24px */
  }

  .welcome-message p:first-child {
    font-size: 1.25rem; /* 20px */
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem; /* 4px */
  }

  .welcome-message p.small {
    font-size: 0.875rem; /* 14px */
    color: var(--text-secondary);
    margin: 0;
  }

  /* Alert */
  .alert {
    font-size: 0.9375rem; /* 15px */
    padding: 0.75rem 1.25rem; /* 12px 20px */
    margin-bottom: 1.5rem; /* 24px */
    border-radius: 0.375rem; /* 6px */
    border: 1px solid transparent;
  }

  .alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border-color: rgba(40, 167, 69, 0.2);
    color: var(--success-color);
  }

  .alert-danger {
    background-color: rgba(220, 53, 69, 0.15);
    border-color: rgba(220, 53, 69, 0.2);
    color: var(--danger-color);
    padding: 0.75rem 1.25rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .alert-danger i {
    font-size: 1.25rem;
    flex-shrink: 0;
  }

  .alert-danger .btn-close {
    padding: 0;
    margin-left: 1rem;
    filter: invert(25%) sepia(63%) saturate(2836%) hue-rotate(337deg) brightness(92%) contrast(89%);
  }

  .alert-dismissible .btn-close {
    padding: 0.75rem 1.25rem; /* 12px 20px */
  }

  /* Form Login */
  #loginForm {
    margin-top: 1.5rem; /* 24px */
  }

  .form-group {
    margin-bottom: 1.25rem; /* 20px */
  }

  .form-label {
    display: block;
    font-size: 0.9375rem; /* 15px */
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem; /* 8px */
  }

  .form-control {
    display: block;
    width: 100%;
    font-size: 0.9375rem; /* 15px */
    padding: 0.75rem; /* 12px */
    line-height: 1.5;
    color: var(--text-primary);
    background-color: var(--white);
    background-clip: padding-box;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem; /* 8px */
    transition: var(--transition);
  }

  .form-control:focus {
    border-color: var(--primary-color);
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(252, 200, 34, 0.25);
  }

  .form-control::placeholder {
    color: var(--text-light);
    opacity: 1;
    font-size: 0.875rem; /* 14px */
  }

  /* Password Input Group */
  .password-input-group {
    position: relative;
  }

  .password-toggle {
    position: absolute;
    right: 0.9375rem; /* 15px */
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: var(--text-light);
    font-size: 1rem; /* 16px */
    transition: var(--transition);
  }

  .password-toggle:hover {
    color: var(--text-primary);
  }

  /* Tombol Login */
  .login-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    font-size: 1rem; /* 16px */
    font-weight: 600;
    color: var(--white);
    background-color: var(--black);
    border: none;
    border-radius: 0.5rem; /* 8px */
    padding: 0.75rem; /* 12px */
    margin-top: 1.25rem; /* 20px */
    cursor: pointer;
    transition: var(--transition);
  }

  .login-btn:hover {
    background-color: #222222;
    color: var(--white);
    transform: translateY(-2px);
  }

  .login-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
  }

  .spinner-border {
    display: inline-block;
    width: 1rem; /* 16px */
    height: 1rem; /* 16px */
    vertical-align: -0.125em;
    border: 0.15em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
  }

  @keyframes spinner-border {
    to { transform: rotate(360deg); }
  }

  /* Link Register */
  .register-link {
    text-align: center;
    margin-top: 1.25rem; /* 20px */
  }

  .register-link p {
    font-size: 0.875rem; /* 14px */
    color: var(--text-primary);
    margin: 0;
  }

  .register-link a {
    font-size: 0.875rem; /* 14px */
    font-weight: 600;
    color: #000;
    text-decoration: none;
    transition: var(--transition);
  }

  .register-link a:hover {
    color: var(--white);
    text-decoration: underline;
  }

  /* Responsive Design */
  @media (max-width: 991.98px) {
    .login-box {
      max-width: 48rem; /* 768px */
    }
  }

  @media (max-width: 767.98px) {
    .login-box {
      flex-direction: column;
      max-width: 30rem; /* 480px */
    }
    
    .left-section,
    .right-section {
      flex: 0 0 100%;
      max-width: 100%;
    }
    
    .left-section {
      padding: 1.875rem; /* 30px */
      border-radius: 0.9375rem 0.9375rem 0 0; /* 15px */
    }
    
    .right-section {
      padding: 1.875rem; /* 30px */
      border-radius: 0 0 0.9375rem 0.9375rem; /* 15px */
    }
    
    .login-header h1 {
      font-size: 1.75rem; /* 28px */
    }
    
    .welcome-message p:first-child {
      font-size: 1.125rem; /* 18px */
    }
  }

  @media (max-width: 575.98px) {
    .login-container {
      padding: 1rem; /* 16px */
    }

    .alert-danger {
    padding: 0.625rem 1rem;
    flex-direction: column;
    align-items: flex-start;
  }
  
    .alert-danger .btn-close {
      align-self: flex-end;
      margin-top: 0.5rem;
      margin-left: 0;
    }
    
    .left-section,
    .right-section {
      padding: 1.5625rem; /* 25px */
    }
    
    .login-header h1 {
      font-size: 1.5rem; /* 24px */
    }
    
    .form-control {
      padding: 0.625rem; /* 10px */
    }
    
    .alert {
      padding: 0.625rem 1rem; /* 10px 16px */
    }
  }
  </style>
  <link rel="icon" href="img/Q!.ico" type="image/x-icon" />
</head>
<body>

  <div class="login-container">
    <div class="login-box shadow-lg row g-0">
      <!-- Left Section (Image) -->
      <div class="left-section col-lg-5 d-none d-lg-flex">
        <img src="img/login.jpg" alt="Login Illustration" class="img-fluid" />
      </div>
      
      <!-- Right Section (Form) -->
      <div class="right-section col-lg-7 col-md-12">
        <div class="login-header d-flex justify-content-between align-items-center">
          <h1>Qu<span>easy</span></h1>
          <a href="index.php" class="btn btn-sm back-btn text-black">
            <i class="fas fa-arrow-left me-1"></i> Kembali
          </a>
        </div>
        
        <div class="welcome-message mb-4">
          <p class="mb-1 fw-bold">Selamat Datang Kembali!</p>
          <p class="text-muted">Login ke akunmu</p>
        </div>
        
        <!-- Alert Success -->
        <?php if ($success) : ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <!-- Alert Error -->
        <?php if ($error) : ?>
          <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
              <i class="fas fa-exclamation-circle me-2"></i>&nbsp;
              <div><?= htmlspecialchars($error) ?></div>
            </div>
          </div>
        <?php endif; ?>
        
        <form action="" method="POST" id="loginForm">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" 
                  placeholder="Masukkan username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required />
          </div>
          
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="password-input-group">
              <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required />
              <i class="password-toggle fas fa-eye" id="passwordToggle"></i>
            </div>
          </div>
          
          <button type="submit" class="btn login-btn" id="loginBtn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Login
          </button>
          
          <div class="register-link mt-3">
            <p class="small">Belum punya akun? <a href="register.php">Daftar</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Toggle password visibility
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordInput = document.getElementById('password');
    
    passwordToggle.addEventListener('click', function() {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.classList.remove('fa-eye');
        passwordToggle.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        passwordToggle.classList.remove('fa-eye-slash');
        passwordToggle.classList.add('fa-eye');
      }
    });
    
    // Loading state untuk tombol login
    document.getElementById('loginForm').addEventListener('submit', function() {
      const btn = document.getElementById('loginBtn');
      const spinner = btn.querySelector('.spinner-border');
      
      btn.disabled = true;
      spinner.classList.remove('d-none');
      btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
    });
    
    // Alert sukses login dengan SweetAlert2
    <?php if ($login_success) : ?>
    Swal.fire({
      title: 'Login Berhasil!',
      text: 'Selamat datang, <?= htmlspecialchars($_SESSION["username"]) ?>!',
      icon: 'success',
      confirmButtonText: 'OK',
      confirmButtonColor: '#fcc822',
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false
    }).then((result) => {
      window.location.href = '<?= $redirect_url ?>';
    });
    <?php endif; ?>
  </script>

</body>
</html>