<?php
  session_start();
  require_once(__DIR__ . "/layout/functions.php");

  if (isset($_SESSION["login"])) {
      header("Location: index.php");
      exit;
  }

  $error = false;
  $success = '';
  $login_success = false;

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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Queasy</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="login.css" />
</head>
<body>

  <div class="login d-flex justify-content-center align-items-center">
    <div class="container main">
      <div class="row border rounded-4 box-area shadow-lg">

        <!-- Left Box -->
        <div class="col-md-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #faf7f4; border-radius: 15px 0 0 15px;">
          <img src="img/login.jpg" alt="login" class="img-fluid rounded" />
        </div>

        <!-- Right Box -->
        <div class="col-md-8 right-box" style="background-color: #fcc822; border-radius: 0 15px 15px 0;">
          <div class="d-flex justify-content-between align-items-center header mt-4 mx-4">
            <h1>Qu<span>easy</span></h1>
            <a href="index.php" class="btn btn-outline-light btn-sm">← Kembali</a>
          </div>

          <div class="content mx-5 my-4">
            <p class="m-0">Selamat Datang Kembali!</p>
            <p class="mb-2" style="font-size: 13px;">Login ke akunmu</p>

            <!-- Alert Success -->
            <?php if ($success) : ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <!-- Alert Error -->
            <?php if ($error) : ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <form action="" method="POST" id="loginForm">
              <label for="username" class="form-label">Username</label>
              <input type="text" name="username" class="form-control" id="username" 
                    placeholder="Masukkan username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required />

              <label for="password" class="form-label mt-2">Password</label>
              <div class="group-input">
                <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required />
                <img id="passwordIcon" src="img/eye-solid.svg" alt="toggle" onclick="togglePassword()" />
              </div>

              <div class="bottom-container">
                <p><small style="font-size: 13px">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar</a></small></p>
              </div>

              <button type="submit" class="btn btn-outline-dark mt-4" id="loginBtn">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                Login
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
  function togglePassword() {
    const passwordField = document.getElementById("password");
    const passwordIcon = document.getElementById("passwordIcon");
    if (passwordField.type === "password") {
      passwordField.type = "text";
      passwordIcon.src = "img/eye-slash-solid.svg";
    } else {
      passwordField.type = "password";
      passwordIcon.src = "img/eye-solid.svg";
    }
  }

  // Loading state untuk tombol login
  document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    const spinner = btn.querySelector('.spinner-border');
    
    btn.disabled = true;
    spinner.classList.remove('d-none');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
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
    window.location.href = 'index.php';
  });
  <?php endif; ?>

  // Form validation
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      const forms = document.getElementsByClassName('needs-validation');
      const validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>
</html>