<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <!-- Logo -->
    <p class="navbar-brand mx-4 mb-0 h1 logo">Qu<span>easy</span></p>
    
    <!-- Mobile toggle button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fa-solid fa-bars"></i>
    </button>
    
    <!-- Navbar items -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0" id="navbarList">
        <?php if(isset($_SESSION["admin"])) : ?>
        <hr>
        <li class="nav-item">
          <a class="nav-link me-2 ms-2 " href="/Queasy/admin/"><i class="fa-solid fa-user-gear"></i> Admin</a>
        </li>
        <?php endif ?>
        
        <?php if(isset($_SESSION["login"])) : ?>
          <hr>
          <li class="nav-item">
            <a class="nav-link active mx-2 " aria-current="page" href="/Queasy/index.php"><i class="fa-solid fa-house"></i> Beranda</a>
          </li>
          <hr>
          <li class="nav-item">
            <a href="/Queasy/user/leaderboard/leaderboard.php" class="nav-link mx-2 "><i class="fa-solid fa-trophy"></i> Rank</a>
          </li>
          <hr>
          <li class="nav-item w-100 d-block d-lg-none py-2">
            <div class="dropdown">
              <button class="btn btn-dark dropdown-toggle rounded-3 w-100 d-flex justify-content-between align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span>
                  <i class="fa-solid fa-user me-2" style="color: #fcc822;"></i>
                  <?php echo $_SESSION["username"]; ?>
                </span>
              </button>
              <ul class="dropdown-menu w-100 border-0 shadow">
                <li><a class="dropdown-item py-2" href="/Queasy/user/profile.php">
                  <i class="fa-solid fa-user-pen me-2"></i>Profil
                </a></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item py-2 text-danger" href="/Queasy/logout.php">
                  <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar
                </a></li>
              </ul>
            </div>
          </li>

          <!-- Versi desktop -->
          <li class="nav-item d-none d-lg-block">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle rounded-5 bg-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-user" style="color: #fcc822;"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li class="dropdown-item disabled"><?php echo $_SESSION["username"]; ?></li>
                <li><a class="dropdown-item" href="/Queasy/user/profile.php">Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-decoration-none" href="/Queasy/logout.php">Keluar</a></li>
              </ul>
            </div>
          </li>
        <?php else: ?>
          <!-- Mobile register/login buttons (visible only on mobile when not logged in) -->
          <hr>
          <li class="nav-item d-lg-none ">
            <a class="nav-link" href="/Queasy/login.php"><i class="fa-solid fa-right-to-bracket"></i> Masuk</a>
          </li>
          <hr>
          <li class="nav-item d-lg-none ">
            <a class="nav-link" href="/Queasy/register.php"><i class="fa-solid fa-user-plus"></i> Daftar</a>
          </li>
        <?php endif ?>
      </ul>
    </div>
  </div>
</nav>