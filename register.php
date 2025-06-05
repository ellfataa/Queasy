<?php
  require_once(__DIR__ . "/layout/functions.php");

  $errors = [];
  $success = '';

  if (isset($_POST["register"])) {
      $result = register($_POST, $errors);
      if ($result > 0) {
          $success = 'User baru berhasil ditambahkan. Silakan login.';
      }
  }
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign Up - Queasy</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

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
      font-size: 1rem;
      line-height: 1.5;
      color: var(--text-primary);
      display: flex;
      flex-direction: column;
    }

    /* Layout Utama */
    .register-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.25rem;
    }

    .register-box {
      width: 100%;
      max-width: 62.5rem;
      border-radius: 0.9375rem;
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
      padding: 2.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .left-section img {
      max-width: 100%;
      height: auto;
      border-radius: 0.625rem;
      object-fit: cover;
    }

    /* Bagian Kanan (Form) */
    .right-section {
      flex: 0 0 58.333333%;
      max-width: 58.333333%;
      background-color: var(--primary-color);
      padding: 2.5rem;
    }

    /* Header */
    .register-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.875rem;
    }

    .register-header h1 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-primary);
      margin: 0;
      line-height: 1.2;
    }

    .register-header h1 span {
      color: var(--white);
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      font-size: 0.875rem;
      color: var(--text-primary);
      text-decoration: none;
      transition: var(--transition);
      padding: 0.3125rem 0.625rem;
      border-radius: 0.3125rem;
    }

    .back-btn:hover {
      background-color: rgba(0, 0, 0, 0.05);
      color: var(--text-primary);
    }

    .back-btn i {
      margin-right: 0.3125rem;
    }

    /* Pesan Selamat Datang */
    .welcome-message {
      margin-bottom: 1.5rem;
    }

    .welcome-message p:first-child {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }

    .welcome-message p.small {
      font-size: 0.875rem;
      color: var(--text-secondary);
      margin: 0;
    }

    /* Alert */
    .alert {
      font-size: 0.9375rem;
      padding: 0.75rem 1.25rem;
      margin-bottom: 1.5rem;
      border-radius: 0.375rem;
      border: 1px solid transparent;
    }

    .alert-danger {
      background-color: rgba(220, 53, 69, 0.1);
      border-color: rgba(220, 53, 69, 0.2);
      color: var(--danger-color);
    }

    .alert ul {
      margin-bottom: 0;
      padding-left: 1.25rem;
    }

    /* Form */
    #registerForm {
      margin-top: 1.5rem;
    }

    .form-label {
      display: block;
      font-size: 0.9375rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .form-control {
      display: block;
      width: 100%;
      font-size: 0.9375rem;
      padding: 0.75rem;
      line-height: 1.5;
      color: var(--text-primary);
      background-color: var(--white);
      background-clip: padding-box;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
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
      font-size: 0.875rem;
    }

    /* Password Input Group */
    .password-group {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      right: 0.9375rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--text-light);
      font-size: 1rem;
      transition: var(--transition);
      z-index: 10;
    }

    .password-toggle:hover {
      color: var(--text-primary);
    }

    /* Tombol register */
    .register-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      font-size: 1rem;
      font-weight: 600;
      color: var(--white);
      background-color: var(--black);
      border: none;
      border-radius: 0.5rem;
      padding: 0.75rem;
      margin-top: 1.25rem;
      cursor: pointer;
      transition: var(--transition);
    }

    .register-btn:hover {
      background-color: #222222;
      color: var(--white);
      transform: translateY(-2px);
    }

    .register-btn:disabled {
      opacity: 0.65;
      cursor: not-allowed;
      transform: none;
    }

    .spinner-border {
      display: inline-block;
      width: 1rem;
      height: 1rem;
      vertical-align: -0.125em;
      border: 0.15em solid currentColor;
      border-right-color: transparent;
      border-radius: 50%;
      animation: spinner-border 0.75s linear infinite;
    }

    @keyframes spinner-border {
      to { transform: rotate(360deg); }
    }

    /* Link login */
    .login-link {
      text-align: center;
      margin-top: 1.25rem;
    }

    .login-link p {
      font-size: 0.875rem;
      color: var(--text-primary);
      margin: 0;
    }

    .login-link a {
      font-size: 0.875rem;
      font-weight: 600;
      color: #000;
      text-decoration: none;
      transition: var(--transition);
    }

    .login-link a:hover {
      color: var(--white);
      text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 991.98px) {
      .register-box {
        max-width: 48rem;
      }
    }

    @media (max-width: 767.98px) {
      .register-box {
        flex-direction: column;
        max-width: 30rem;
      }
      
      .left-section,
      .right-section {
        flex: 0 0 100%;
        max-width: 100%;
      }
      
      .left-section {
        padding: 1.875rem;
        border-radius: 0.9375rem 0.9375rem 0 0;
      }
      
      .right-section {
        padding: 1.875rem;
        border-radius: 0 0 0.9375rem 0.9375rem;
      }
      
      .register-header h1 {
        font-size: 1.75rem;
      }
      
      .welcome-message p:first-child {
        font-size: 1.125rem;
      }
    }

    @media (max-width: 575.98px) {
      .register-container {
        padding: 1rem;
      }
      
      .left-section,
      .right-section {
        padding: 1.5625rem;
      }
      
      .register-header h1 {
        font-size: 1.5rem;
      }
      
      .form-control {
        padding: 0.625rem;
      }
      
      .alert {
        padding: 0.625rem 1rem;
      }
    }
  </style>
  <link rel="icon" href="img/Q!.ico" type="image/x-icon" />
</head>
<body>

  <div class="register-container">
    <div class="register-box shadow-lg">
      <!-- Left Section (Image) -->
      <div class="left-section d-none d-lg-flex">
        <img src="img/login.jpg" alt="Register Illustration" class="img-fluid" />
      </div>
      
      <!-- Right Section (Form) -->
      <div class="right-section">
        <div class="register-header">
          <h1>Qu<span>easy</span></h1>
          <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
        
        <div class="welcome-message">
          <p>Selamat Datang di Queasy!</p>
          <p class="small">Buat akun dengan 2 langkah</p>
        </div>
        
        <!-- Error Display -->
        <?php if (!empty($errors)) : ?>
          <div class="alert alert-danger">
            <ul>
              <?php foreach ($errors as $err) : ?>
                <li><?= htmlspecialchars($err) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        
        <form action="" method="POST" id="registerForm">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" 
                   placeholder="Masukkan nama" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" 
                   required autofocus autocomplete="off" />
          </div>
          
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" 
                   placeholder="Masukkan email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" 
                   required />
          </div>
          
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="password-group">
              <input type="password" name="password" class="form-control" id="password" 
                     placeholder="Masukkan password" required />
              <i class="password-toggle fas fa-eye" id="passwordToggle"></i>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="password2" class="form-label">Konfirmasi Password</label>
            <div class="password-group">
              <input type="password" name="password2" class="form-control" id="password2" 
                     placeholder="Masukkan password lagi" required />
              <i class="password-toggle fas fa-eye" id="passwordToggle2"></i>
            </div>
          </div>
          
          <button type="submit" name="register" class="register-btn" id="registerBtn">
            Register
          </button>
          
          <div class="login-link">
            <p><small>Sudah punya akun? <a href="login.php">Login</a></small></p>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Toggle password visibility
    function togglePassword() {
      const passwordField = document.getElementById("password");
      const passwordIcon = document.getElementById("passwordToggle");
      if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordIcon.classList.remove("fa-eye");
        passwordIcon.classList.add("fa-eye-slash");
      } else {
        passwordField.type = "password";
        passwordIcon.classList.remove("fa-eye-slash");
        passwordIcon.classList.add("fa-eye");
      }
    }

    function togglePassword2() {
      const passwordField2 = document.getElementById("password2");
      const passwordIcon2 = document.getElementById("passwordToggle2");
      if (passwordField2.type === "password") {
        passwordField2.type = "text";
        passwordIcon2.classList.remove("fa-eye");
        passwordIcon2.classList.add("fa-eye-slash");
      } else {
        passwordField2.type = "password";
        passwordIcon2.classList.remove("fa-eye-slash");
        passwordIcon2.classList.add("fa-eye");
      }
    }

    // Event listeners for password toggles
    document.getElementById('passwordToggle').addEventListener('click', togglePassword);
    document.getElementById('passwordToggle2').addEventListener('click', togglePassword2);
    
    // Alert sukses registrasi dengan SweetAlert2
    <?php if (!empty($success)) : ?>
    Swal.fire({
      title: 'Berhasil!',
      text: '<?= $success ?>',
      icon: 'success',
      confirmButtonText: 'OK',
      confirmButtonColor: '#fcc822',
      background: '#faf7f4',
      color: '#18152d'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'login.php';
      }
    });
    <?php endif; ?>
  </script>

</body>
</html>