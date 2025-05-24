<?php 
session_start();

// Include required files
require_once(__DIR__ . "/../layout/functions.php");

// Check if user is logged in and is admin
if(!isset($_SESSION['username'])) {
    header('location:../login.php');
    exit;
}

if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('location:../index.php');
    exit;
}

// Function to count records in a table
function countRecords($mysqli, $table) {
    $query = "SELECT COUNT(*) as total FROM $table";
    $result = mysqli_query($mysqli, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

// Get counts from database
$userCount = countRecords($mysqli, "user");
$categoryCount = countRecords($mysqli, "category");
$quizCount = countRecords($mysqli, "quizzes");
$questionCount = countRecords($mysqli, "questions");
$optionCount = countRecords($mysqli, "options");

// Handle content routing
$currentPage = isset($_GET['content']) ? $_GET['content'] : 'dashboard';
$pageTitle = ucfirst($currentPage);
if($currentPage == 'dashboard') $pageTitle = 'Dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | Queasy Admin</title>
    
    <link rel="icon" href="../img/q!.ico" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6a5acd;
            --secondary: #4CAF50;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-header .logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        
        .sidebar-header h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            transition: opacity 0.3s;
        }
        
        .sidebar.collapsed .sidebar-header h4,
        .sidebar.collapsed .sidebar-header .user-info {
            opacity: 0;
            transform: scale(0);
        }
        
        .user-panel {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
            text-align: center;
        }
        
        .user-panel .user-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .user-panel .user-role {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .nav-menu {
            padding: 20px 0;
        }
        
        .nav-item {
            margin-bottom: 8px;
            padding: 0 15px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            font-weight: 500;
        }
        
        .nav-link i {
            min-width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        .sidebar.collapsed .nav-link span {
            opacity: 0;
            transform: scale(0);
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        /* Top Header */
        .top-header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .header-left {
            display: flex;
            align-items: center;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--dark);
            margin-right: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .sidebar-toggle:hover {
            background: var(--light);
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding: 8px 15px 8px 40px;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            width: 250px;
            font-size: 0.9rem;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        /* Content Area */
        .content-area {
            padding: 30px;
        }
        
        /* Dashboard Cards */
        .dashboard-overview {
            margin-bottom: 30px;
        }
        
        .welcome-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .welcome-title {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        
        .welcome-subtitle {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-color, var(--primary));
        }
        
        .stat-card.info::before { background: var(--info); }
        .stat-card.success::before { background: var(--secondary); }
        .stat-card.warning::before { background: var(--warning); }
        .stat-card.primary::before { background: var(--primary); }
        .stat-card.danger::before { background: var(--danger); }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-icon.info { background: var(--info); }
        .stat-icon.success { background: var(--secondary); }
        .stat-icon.warning { background: var(--warning); }
        .stat-icon.primary { background: var(--primary); }
        .stat-icon.danger { background: var(--danger); }
        
        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }
        
        .stat-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        
        .stat-link:hover {
            color: var(--dark);
        }
        
        .stat-link i {
            margin-left: 8px;
            transition: transform 0.3s;
        }
        
        .stat-link:hover i {
            transform: translateX(3px);
        }
        
        /* Status Section */
        .system-status {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .status-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .status-header i {
            color: var(--info);
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .status-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        .status-content {
            color: #6c757d;
            margin: 0;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .search-box input {
                width: 200px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .top-header {
                padding: 15px 20px;
            }
            
            .content-area {
                padding: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .search-box {
                display: none;
            }
            
            .header-right {
                gap: 10px;
            }
        }
        
        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../img/Q!.png" alt="Queasy Logo" class="logo">
            <h4>Queasy Admin</h4>
        </div>
        
        <div class="user-panel">
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            <div class="user-role">Administrator</div>
        </div>
        
        <nav class="nav-menu">
            <div class="nav-item">
                <a href="?content=dashboard" class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=user" class="nav-link <?= $currentPage == 'user' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=category" class="nav-link <?= $currentPage == 'category' ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=quiz" class="nav-link <?= $currentPage == 'quiz' ? 'active' : '' ?>">
                    <i class="fas fa-gamepad"></i>
                    <span>Quizzes</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=questions" class="nav-link <?= $currentPage == 'questions' ? 'active' : '' ?>">
                    <i class="fas fa-question-circle"></i>
                    <span>Questions</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="?content=options" class="nav-link <?= $currentPage == 'options' ? 'active' : '' ?>">
                    <i class="fas fa-list-ul"></i>
                    <span>Options</span>
                </a>
            </div>
            <div class="nav-item" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                <a href="../logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?php echo $pageTitle; ?></h1>
            </div>
            <div class="header-right">
                <a href="../index.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <?php if($currentPage == 'dashboard'): ?>
                <!-- Dashboard Content -->
                <div class="dashboard-overview">
                    <div class="welcome-section">
                        <h2 class="welcome-title">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card info">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-number"><?php echo $userCount; ?></div>
                                    <div class="stat-label">Total Users</div>
                                </div>
                                <div class="stat-icon info">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <a href="?content=user" class="stat-link">
                                View Details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="stat-card success">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-number"><?php echo $categoryCount; ?></div>
                                    <div class="stat-label">Categories</div>
                                </div>
                                <div class="stat-icon success">
                                    <i class="fas fa-tags"></i>
                                </div>
                            </div>
                            <a href="?content=category" class="stat-link">
                                Manage Categories <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="stat-card warning">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-number"><?php echo $quizCount; ?></div>
                                    <div class="stat-label">Active Quizzes</div>
                                </div>
                                <div class="stat-icon warning">
                                    <i class="fas fa-gamepad"></i>
                                </div>
                            </div>
                            <a href="?content=quiz" class="stat-link">
                                Manage Quizzes <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="stat-card primary">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-number"><?php echo $questionCount; ?></div>
                                    <div class="stat-label">Total Questions</div>
                                </div>
                                <div class="stat-icon primary">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                            </div>
                            <a href="?content=questions" class="stat-link">
                                View Questions <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="stat-card danger">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-number"><?php echo $optionCount; ?></div>
                                    <div class="stat-label">Answer Options</div>
                                </div>
                                <div class="stat-icon danger">
                                    <i class="fas fa-list-ul"></i>
                                </div>
                            </div>
                            <a href="?content=options" class="stat-link">
                                Manage Options <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="stat-card" style="border-image: linear-gradient(135deg, #667eea, #764ba2) 1;">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-number"><i class="fas fa-plus-circle"></i></div>
                                    <div class="stat-label">Quick Actions</div>
                                </div>
                                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                    <i class="fas fa-bolt"></i>
                                </div>
                            </div>
                            <a href="?content=quiz" class="stat-link">
                                Create New Quiz <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="system-status">
                        <div class="status-header">
                            <i class="fas fa-info-circle"></i>
                            <h3 class="status-title">System Status</h3>
                        </div>
                        <p class="status-content">
                            All systems are running smoothly. Database is operational and all services are up. 
                            Last updated: <?php echo date('Y-m-d H:i:s'); ?>
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Dynamic Content Loading -->
                <div class="content-placeholder">
                    <?php
                    // Include content based on the current page
                    switch($currentPage) {
                        case 'user':
                            if(file_exists('view_user.php')) {
                                include 'view_user.php';
                            } else {
                                echo '<div class="alert alert-warning">User management page not found.</div>';
                            }
                            break;
                        case 'category':
                            if(file_exists('view_category.php')) {
                                include 'view_category.php';
                            } else {
                                echo '<div class="alert alert-warning">Category management page not found.</div>';
                            }
                            break;
                        case 'quiz':
                            if(file_exists('view_quiz.php')) {
                                include 'view_quiz.php';
                            } else {
                                echo '<div class="alert alert-warning">Quiz management page not found.</div>';
                            }
                            break;
                        case 'questions':
                            if(file_exists('view_question.php')) {
                                include 'view_question.php';
                            } else {
                                echo '<div class="alert alert-warning">Questions management page not found.</div>';
                            }
                            break;
                        case 'options':
                            if(file_exists('view_option.php')) {
                                include 'view_option.php';
                            } else {
                                echo '<div class="alert alert-warning">Options management page not found.</div>';
                            }
                            break;
                        case 'edit':
                            if(file_exists('edit.php')) {
                                include 'edit.php';
                            } else {
                                echo '<div class="alert alert-warning">Edit page not found.</div>';
                            }
                            break;
                        case 'create':
                            if(file_exists('create.php')) {
                                include 'create.php';
                            } else {
                                echo '<div class="alert alert-warning">Create page not found.</div>';
                            }
                            break;
                        default:
                            echo '<div class="alert alert-info">Page not found. Please select a valid menu item.</div>';
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const searchInput = document.getElementById('searchInput');
            
            // Sidebar toggle functionality
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                }
            });
            
            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
            
            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const tables = document.querySelectorAll('table tbody tr');
                    
                    tables.forEach(function(row) {
                        const text = row.textContent.toLowerCase();
                        if (text.indexOf(value) > -1) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                }
            });
            
            // Smooth scrolling for sidebar links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Add loading state if needed
                    if (this.href && !this.href.includes('#')) {
                        const icon = this.querySelector('i');
                        if (icon && !icon.classList.contains('fa-sign-out-alt')) {
                            const originalClass = icon.className;
                            icon.className = 'fas fa-spinner fa-spin';
                            
                            // Restore original icon after a delay
                            setTimeout(() => {
                                icon.className = originalClass;
                            }, 1000);
                        }
                    }
                });
            });
            
            // Auto-hide mobile sidebar after navigation
            if (window.innerWidth <= 768) {
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        setTimeout(() => {
                            sidebar.classList.remove('show');
                        }, 300);
                    });
                });
            }
        });
    </script>
</body>
</html>