<nav class="navbar">
  <div class="container-fluid">
    <!-- Logo -->
    <p class="navbar-brand mb-0 h1 logo">Qu<span>easy</span></p>
    
    <!-- Navbar items -->
    <ul class="nav" id="navbarList">
      <?php if(isset($_SESSION["admin"])) : ?>
      <li class="nav-item">
        <a class="nav-link me-2 ms-2" href="/Queasy/admin/"><i class="fa-solid fa-user-gear"></i> Admin</a>
      </li>
      <?php endif ?>
      
      <?php if(isset($_SESSION["login"])) : ?>
        <li class="nav-item">
          <a class="nav-link active mx-2" aria-current="page" href="/Queasy/index.php"><i class="fa-solid fa-house"></i> Beranda</a>
        </li>
        <li class="nav-item">
          <a href="/Queasy/user/leaderboard/leaderboard.php" class="nav-link ms-3"><i class="fa-solid fa-trophy"></i> Rank</a>
        </li>
        <li class="nav-item">
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle rounded-5 bg-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-user" style="color: #fcc822;"></i>
            </button>
            <ul class="dropdown-menu">
              <li class="dropdown-item"><?php echo $_SESSION["username"]; ?></li>
              <li><a class="dropdown-item" href="/Queasy/user/profile.php">Profil</a></li>
              <!-- <li><a class="dropdown-item" href="/Queasy/user/quiz/my_quiz.php">Kuisku</a></li> -->
              <li><hr class="dropdown-divider"></li>
              <li><a href="/Queasy/logout.php"><button type="button" class="dropdown-item text-decoration-none">Keluar</button></a></li>
            </ul>
          </div>
        </li>
      <?php endif ?>
    </ul>
  </div>
</nav>
