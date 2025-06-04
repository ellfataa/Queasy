<?php
  session_start();
  require_once(__DIR__ . "/layout/functions.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSS Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  
  <!-- Font dan Ikon -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <link rel="stylesheet" href="index.css" />
  <link rel="icon" href="img/q!.ico" type="image/x-icon">

  <title>Queasy - Selamat Datang!</title>
</head>
<body>

  <!-- Navbar -->
  <?php include(__DIR__ . "/layout/navbar.php"); ?>

  <!-- Kategori (hanya jika sudah login) -->
  <?php if (isset($_SESSION["login"])): ?>
    <?php include(__DIR__ . "/user/category/category.php"); ?>
  <?php endif; ?>

  <!-- Hero Section -->
  <?php if (!isset($_SESSION["login"])): ?>
    <!-- Replace the existing hero section with this -->
    <div class="hero">
      <div class="container">
        <div class="row flex-column-reverse flex-md-row">
          <div class="col-lg-6 col-md-12 col-sm-12 m-auto hero-text-section">
            <h1 class="hero-title text-center text-md-start">"Uji Otakmu Dengan Queasy!"</h1>
            <p class="hero-text text-center text-md-start">Platform kuis yang membantu kamu menguji pengetahuan dan meningkatkan kemampuan.</p>
            <!-- Untuk Mobile (sm ke bawah) -->
            <div class="d-flex flex-column d-sm-none justify-content-center gap-3 py-3">
              <a href="login.php" class="d-block text-center">
                <button type="button" class="btn btn-warning shadow py-3 px-4 fs-5 fw-bold w-100" style="min-width: 200px;">
                  <i class="fas fa-play me-2"></i>Mulai Sekarang
                </button>
              </a>
            </div>

            <!-- Untuk Desktop/Laptop (sm ke atas) - Tampilan Asli -->
            <div class="d-none d-sm-flex justify-content-md-start gap-2">
              <a href="login.php">
                <button type="button" class="btn btn-outline-dark btn-warning shadow"><i class="fas fa-play me-2"></i>Mulai</button>
              </a>
              <a href="register.php">
                <button type="button" class="btn btn-outline-dark btn-light shadow sign">Daftar</button>
              </a>
            </div>
          </div>
          <div class="col-lg-6 col-md-12 col-sm-12 image-hero mb-4 mb-md-0">
            <img src="img/hero.png" alt="hero" class="img-fluid hero-img" />
          </div>
        </div>
      </div>
    </div>

    <!-- Bagian 2 - Cara Bermain -->
    <img src="img/wave-top.svg" alt="gelombang-atas" class="w-100">
    <div class="frame2 py-5" id="frame2">
      <div class="container">
        <div class="row text-center mb-4">
          <h1>Cara Bermain</h1>
          <p class="px-3">Ikuti langkah-langkah untuk memulai kuismu. Pelajari aturan, jawab soal, dan raih kemenangan.</p>
        </div>
        <div class="row g-4 d-flex justify-content-center">
          <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card bg-body-tertiary shadow rounded-4 h-100">
              <img src="img/step1.jpg" class="card-img-top mt-3 img-fluid" alt="Langkah 1">
              <div class="card-body text-center">
                <h5 class="card-title">Langkah 1</h5>
                <p class="card-text">Daftar atau masuk ke akunmu</p>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card bg-body-tertiary shadow rounded-4 h-100">
              <img src="img/step2.jpg" class="card-img-top mt-3 img-fluid" alt="Langkah 2">
              <div class="card-body text-center">
                <h5 class="card-title">Langkah 2</h5>
                <p class="card-text">Pilih kategori kuis yang ingin kamu kerjakan</p>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card bg-body-tertiary shadow rounded-4 h-100">
              <img src="img/step3.jpg" class="card-img-top mt-3 img-fluid" alt="Langkah 3">
              <div class="card-body text-center">
                <h5 class="card-title">Langkah 3</h5>
                <p class="card-text">Mulai mengerjakan kuis dengan memilih jawaban yang benar</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <img src="img/wave-bot.svg" alt="gelombang-bawah" class="w-100">

    <!-- Bagian 3 - Dimanapun dan Kapanpun -->
    <div class="frame3 min-vh-100" id="frame3">
      <div class="container">
        <div class="row text-center">
          <h1>Bermain di Mana Saja dan Kapan Saja</h1>
          <p>Bermain sendiri atau bersama teman, di rumah atau di kelas, dengan guru atau secara mandiri.</p>
        </div>
        <div class="row pt-4 text-center">
          <div class="col-lg-4">
            <span class="lingkaran shadow"><i class="fa-solid fa-house-chimney fa-5x"></i></span>
            <p class="mt-4 fw-semibold m-auto">Queasy di Rumah</p>
            <p class="m-auto">Bermain sebagai hiburan keluarga atau bersama teman-teman.</p>
          </div>
          <div class="col-lg-4">
            <span class="lingkaran shadow"><i class="fa-solid fa-school fa-5x"></i></span>
            <p class="mt-4 fw-semibold m-auto">Queasy di Sekolah</p>
            <p class="m-auto">Melibatkan guru dan siswa dalam pembelajaran interaktif online maupun offline.</p>
          </div>
          <div class="col-lg-4">
            <span class="lingkaran shadow"><i class="fa-solid fa-briefcase fa-5x"></i></span>
            <p class="mt-4 fw-semibold m-auto">Queasy di Tempat Kerja</p>
            <p class="m-auto">Jadikan pelatihan, pemecah suasana, atau presentasi lebih menyenangkan.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bagian 4 - Untuk Semua Orang -->
    <div class="frame4 bg-body-secondary py-5">
      <div class="container">
        <h1 class="text-center pt-5">Queasy untuk Semua</h1>
        <p class="text-center px-3">Permudah pembelajaran dan kolaborasi untuk siswa, guru, dan profesional di seluruh dunia.</p>
        <div id="carouselExampleCaptions" class="carousel slide w-100 m-auto my-5" data-bs-ride="carousel">
          <div class="carousel-inner rounded-4">
            <div class="carousel-item active" data-bs-interval="4000">
              <img src="img/item1.jpg" class="d-block w-100" alt="Untuk Guru">
              <div class="carousel-caption d-block">
                <h4>Untuk Guru</h4>
                <p class="text-light fw-light">Alat interaktif favorit untuk mengajar, menilai, dan merevisi.</p>
              </div>
            </div>
            <div class="carousel-item" data-bs-interval="4000">
              <img src="img/item2.jpg" class="d-block w-100" alt="Untuk Siswa">
              <div class="carousel-caption d-block">
                <h4>Untuk Siswa</h4>
                <p class="text-light fw-light">Tahun ajaran baru, cara belajar cerdas baru!</p>
              </div>
            </div>
            <div class="carousel-item" data-bs-interval="4000">
              <img src="img/item3.png" class="d-block w-100" alt="Untuk Keluarga dan Teman">
              <div class="carousel-caption d-block">
                <h4>Untuk Keluarga & Teman</h4>
                <p class="text-light fw-light">Buat kumpul-kumpul jadi seru!</p>
              </div>
            </div>
            <div class="carousel-item" data-bs-interval="4000">
              <img src="img/item4.png" class="d-block w-100" alt="Untuk Profesional">
              <div class="carousel-caption d-block">
                <h4>Untuk Profesional</h4>
                <p class="text-light fw-light">Tingkatkan pelatihan dan presentasimu!</p>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sebelumnya</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Berikutnya</span>
          </button>
        </div>
      </div>
    </div>
  <?php endif; ?>


  <!-- Footer -->
  <footer>
    <div class="container">
      <!-- Desktop/Laptop: Tampilan asli dengan col-4 -->
      <!-- Mobile: Responsive layout -->
      <div class="row d-flex align-items-center py-4 px-md-5 px-3">
        <div class="col-lg-4 col-md-4 col-12 logo text-center text-md-start mb-3 mb-md-0">
          <p class="mb-0">Qu<span>easy</span></p>
        </div>
        
        <div class="col-lg-4 col-md-4 col-6 text-center text-md-start mb-3 mb-md-0">
          <div class="f-head mb-2">MENU</div>
          <div class="mb-2 f-text"><a href="#frame2" class="text-decoration-none">Tentang</a></div>
          <div class="mb-2 f-text"><a href="#" class="text-decoration-none">Layanan</a></div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-6 text-center text-md-start mb-3 mb-md-0">
          <div class="f-head mb-2">IKUTI KAMI</div>
          <div class="mb-2 f-text"><a href="https://www.instagram.com/" class="text-decoration-none">Instagram</a></div>
          <div class="mb-2 f-text"><a href="https://www.facebook.com/" class="text-decoration-none">Facebook</a></div>
        </div>
      </div>
      
      <div class="row">
        <hr class="text-warning border-3">
        <div class="col text-center text-warning">
          <span>&copy; 2025 Qu<span>easy</span>. Semua Hak Dilindungi.</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- JavaScript Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
