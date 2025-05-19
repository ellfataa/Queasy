<?php 
session_start();
if(!isset($_SESSION['username'])) {
    header('location:login.php');
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Quiz System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6a5acd;
            --secondary: #4CAF50;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .welcome-header {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .welcome-header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }
        
        .stat-card {
            border-radius: 12px;
            color: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card .icon {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }
        
        .stat-card .number {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .label {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 1.5rem;
        }
        
        .stat-card .more-link {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        
        .stat-card .more-link:hover {
            opacity: 1;
            text-decoration: underline;
        }
        
        .stat-card .more-link i {
            margin-left: 5px;
            transition: transform 0.3s;
        }
        
        .stat-card .more-link:hover i {
            transform: translateX(3px);
        }
        
        .bg-info { background: linear-gradient(135deg, var(--info), #2dd5e1); }
        .bg-success { background: linear-gradient(135deg, var(--secondary), #6fda44); }
        .bg-warning { background: linear-gradient(135deg, var(--warning), #ffab00); }
        .bg-primary { background: linear-gradient(135deg, var(--primary), #8a6de9); }
        .bg-danger { background: linear-gradient(135deg, var(--danger), #ff6b6b); }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="dashboard-card">
            <h1 class="welcome-header">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
            
            <div class="row">
                <!-- User Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-info">
                        <div class="number"><?php echo $userCount; ?></div>
                        <div class="label">Users</div>
                        <i class="fas fa-users icon"></i>
                        <a href="index.php?content=user" class="more-link">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Category Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-success">
                        <div class="number"><?php echo $categoryCount; ?></div>
                        <div class="label">Categories</div>
                        <i class="fas fa-tags icon"></i>
                        <a href="index.php?content=category" class="more-link">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quiz Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-warning">
                        <div class="number"><?php echo $quizCount; ?></div>
                        <div class="label">Quizzes</div>
                        <i class="fas fa-gamepad icon"></i>
                        <a href="index.php?content=quiz" class="more-link">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Questions Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-primary">
                        <div class="number"><?php echo $questionCount; ?></div>
                        <div class="label">Questions</div>
                        <i class="fas fa-question-circle icon"></i>
                        <a href="index.php?content=questions" class="more-link">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Options Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-danger">
                        <div class="number"><?php echo $optionCount; ?></div>
                        <div class="label">Options</div>
                        <i class="fas fa-cog icon"></i>
                        <a href="index.php?content=options" class="more-link">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>