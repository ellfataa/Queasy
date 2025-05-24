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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="signup.css" />
  
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

  <div class="login d-flex justify-content-center align-items-center">
    <div class="container main">
      <div class="row border rounded-4 box-area shadow-lg">

        <!-- Left Box -->
        <div class="col-md-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #faf7f4; border-radius: 15px 0 0 15px;">
          <img src="img/login.jpg" alt="register illustration" class="img-fluid rounded" />
        </div>

        <!-- Right Box -->
        <div class="col-md-8 right-box" style="background-color: #fcc822; border-radius: 0 15px 15px 0;">
          <div class="d-flex justify-content-between align-items-center header mt-4 mx-4">
            <h1>Qu<span>easy</span></h1>
            <a href="index.php" class="btn btn-outline-light btn-sm">← Kembali</a>
          </div>

          <div class="content mx-5 my-4">
            <p class="m-0">Selamat Datang di Queasy!</p>
            <p style="font-size: 13px;">Buat akun dengan 2 langkah</p>

            <!-- Error Display -->
            <?php if (!empty($errors)) : ?>
              <div class="alert alert-danger">
                <ul class="mb-0">
                  <?php foreach ($errors as $err) : ?>
                    <li><?= htmlspecialchars($err) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form action="" method="POST" id="registerForm">
              <label for="username" class="form-label">Username</label>
              <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan Nama" required autofocus autocomplete="off" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" />

              <label for="email" class="form-label mt-2">Email</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" />

              <label for="password" class="form-label mt-2">Password</label>
              <div class="group-input">
                <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required />
                <img id="passwordIcon" src="img/eye-solid.svg" alt="show" title="Lihat Password" onclick="togglePassword()" />
              </div>

              <label for="password2" class="form-label mt-2">Konfirmasi Password</label>
              <div class="group-input">
                <input type="password" name="password2" class="form-control" id="password2" placeholder="Masukkan password lagi" required />
                <img id="passwordIcon2" src="img/eye-solid.svg" alt="show" title="Lihat Password" onclick="togglePassword2()" />
              </div>

              <div class="bottom-container">
                <p><small style="font-size: 13px">Sudah punya akun?
                <a href="login.php" class="text-decoration-none">Login</a></small></p>
              </div>

              <button type="submit" name="register" class="btn btn-outline-dark mt-2">Register</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- JS Show/Hide Password -->
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

    function togglePassword2() {
      const passwordField2 = document.getElementById("password2");
      const passwordIcon2 = document.getElementById("passwordIcon2");
      if (passwordField2.type === "password") {
        passwordField2.type = "text";
        passwordIcon2.src = "img/eye-slash-solid.svg";
      } else {
        passwordField2.type = "password";
        passwordIcon2.src = "img/eye-solid.svg";
      }
    }

    // Alert untuk success registration
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  
</body>
</html>