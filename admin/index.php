<?php
session_start();
require_once("../functions.php");
if(!isset($_SESSION["login"])) {
    header("Location: ../login.php");
    exit;
}
if(!isset($_SESSION["admin"])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Queasy - Admin</title>
  
  <link rel="icon" href="../img/q!.ico" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Custom CSS -->
  <style>
    :root {
      --primary: #6a5acd;
      --secondary: #4CAF50;
      --light: #f8f9fa;
      --dark: #343a40;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
    }
    
    .brand-link {
      background: rgba(255,255,255,0.1);
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .brand-image {
      height: 33px;
      width: 33px;
      object-fit: contain;
    }
    
    .main-header {
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .nav-sidebar .nav-item > .nav-link {
      margin-bottom: 5px;
      border-radius: 5px;
      transition: all 0.3s;
      color:rgb(255, 255, 255);
    }
    
    .nav-sidebar .nav-item > .nav-link.active {
      background: var(--primary);
      color: white;
    }
    
    .nav-sidebar .nav-item > .nav-link:hover:not(.active) {
      background: rgba(106, 90, 205, 0.1);
    }
    
    .nav-sidebar .nav-treeview .nav-link {
      padding-left: 55px;
    }
    
    .content-wrapper {
      background-color: #f5f7fa;
    }
    
    .content-header {
      padding: 15px 30px 0;
    }
    
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    
    .card-header {
      background: white;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      border-radius: 10px 10px 0 0 !important;
    }
    
    .table {
      border-radius: 10px;
      overflow: hidden;
    }
    
    .table th {
      background: #f8f9fa;
      border-top: none;
    }
    
    .btn-primary {
      background-color: var(--primary);
      border-color: var(--primary);
    }
    
    .btn-primary:hover {
      background-color: #5a4cb3;
      border-color: #5a4cb3;
    }
    
    .user-panel {
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .user-panel .info {
      color: white;
    }
    
    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
      background-color: var(--primary);
    }
    
    .search-box {
      position: relative;
    }
    
    .search-box .form-control {
      padding-left: 40px;
      border-radius: 20px;
      border: 1px solid #e0e0e0;
    }
    
    .search-box .search-icon {
      position: absolute;
      left: 15px;
      top: 10px;
      color: #6c757d;
    }
    
    .main-footer {
      background: white;
      padding: 15px;
      border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    .breadcrumb {
      background: transparent;
      padding: 0;
    }
    /* Custom Sidebar Styles */
    .main-sidebar {
        background: linear-gradient(135deg,rgb(0, 0, 0),rgb(54, 73, 106));
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }

    .brand-link {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 15px 10px;
    }

    .brand-text {
        font-size: 1.1rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        font-style: normal;
    }

    .user-panel {
        padding: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .nav-sidebar > .nav-item > .nav-link {
        margin-bottom: 5px;
        border-radius: 6px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .nav-sidebar > .nav-item > .nav-link.active {
        background: rgba(255,255,255,0.9);
        color: #2c3e50 !important;
        font-weight: 500;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .nav-sidebar > .nav-item > .nav-link:not(.active):hover {
        background: rgba(255,255,255,0.15);
        transform: translateX(3px);
    }

    .nav-sidebar .nav-icon {
        font-size: 1.1rem;
        min-width: 25px;
    }

    .nav-sidebar .nav-link p {
        font-size: 0.95rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .brand-text {
            display: none;
        }
        
        .brand-link {
            justify-content: center;
        }
        
        .user-panel .info {
            display: none;
        }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../index.php" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Search -->
      <li class="nav-item search-box">
        <div class="d-flex align-items-center">
          <i class="fas fa-search search-icon"></i>
          <input class="form-control form-control-sm" id="searchInput" type="search" placeholder="Search...">
        </div>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <p href="index.php" class="brand-link text-center font-weight-normal py-3">
          <img src="../img/Q!.png" alt="Logo Queasy" class="brand-image" style="width: 38px; height: 38px; object-fit: contain;">
          <span class="brand-text font-weight-light ml-2">Queasy Admin</span>
      </p>

      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel mt-3 d-flex align-items-center">
              <div class="info">
                  <span class="d-block text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                  <small class="text-white-50">Administrator</small>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-4">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  <!-- Dashboard -->
                  <li class="nav-item mb-2">
                      <a href="index.php" class="nav-link <?= !isset($_GET['content']) ? 'active' : '' ?>">
                          <i class="nav-icon fas fa-tachometer-alt"></i>
                          <p class="ml-2">Dashboard</p>
                      </a>
                  </li>
                  
                  <!-- User Management -->
                  <li class="nav-item mb-2">
                      <a href="index.php?content=user" class="nav-link <?= isset($_GET['content']) && $_GET['content'] == "user" ? 'active' : '' ?>">
                          <i class="nav-icon fas fa-users"></i>
                          <p class="ml-2">User Management</p>
                      </a>
                  </li>
                  
                  <!-- Categories -->
                  <li class="nav-item mb-2">
                      <a href="index.php?content=category" class="nav-link <?= isset($_GET['content']) && $_GET['content'] == "category" ? 'active' : '' ?>">
                          <i class="nav-icon fas fa-tags"></i>
                          <p class="ml-2">Categories</p>
                      </a>
                  </li>
                  
                  <!-- Quizzes -->
                  <li class="nav-item mb-2">
                      <a href="index.php?content=quiz" class="nav-link <?= isset($_GET['content']) && $_GET['content'] == "quiz" ? 'active' : '' ?>">
                          <i class="nav-icon fas fa-gamepad"></i>
                          <p class="ml-2">Quizzes</p>
                      </a>
                  </li>
                  
                  <!-- Questions -->
                  <li class="nav-item mb-2">
                      <a href="index.php?content=questions" class="nav-link <?= isset($_GET['content']) && $_GET['content'] == "questions" ? 'active' : '' ?>">
                          <i class="nav-icon fas fa-question-circle"></i>
                          <p class="ml-2">Questions</p>
                      </a>
                  </li>
                  
                  <!-- Options -->
                  <li class="nav-item">
                      <a href="index.php?content=options" class="nav-link <?= isset($_GET['content']) && $_GET['content'] == "options" ? 'active' : '' ?>">
                          <i class="nav-icon fas fa-list-ul"></i>
                          <p class="ml-2">Options</p>
                      </a>
                  </li>
              </ul>
          </nav>
      </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <section class="content">
      <div class="container-fluid">
        <?php
        if(isset($_GET['content'])) {
          $content = $_GET['content'];
          if($content == "user") {
            include "view_user.php";
          } else if($content == "quiz") {
            include "view_quiz.php";
          } else if($content == "questions") {
            include "view_question.php";
          } else if($content == "category") {
            include "view_category.php";
          } else if($content == "options") {
            include "view_option.php";
          } else if($content == "edit") {
            include "edit.php";
          } else if($content == "create") {
            include "create.php";
          }
        } else {
          include "dashboard.php";
        }
        ?>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#">Queasy</a>.</strong> All rights reserved.
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Custom JS -->
<script>
$(document).ready(function() {
  // Search functionality
  $('#searchInput').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $('table tbody tr').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
</body>
</html>