<?php
session_start();
require_once("../functions.php");

// Check authentication and admin status
if(!isset($_SESSION["login"])) {
    header("Location: ../login.php");
    exit;
}
if(!isset($_SESSION["admin"])) {
    header("Location: ../index.php");
    exit;
}

// Get all users from database
$result = mysqli_query($mysqli, "SELECT * FROM user");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Queasy Admin</title>
    
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
            --danger: #dc3545;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
        }
        
        .page-header {
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
        }
        
        .card-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 25px;
        }
        
        .table th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
        }
        
        .table td, .table th {
            vertical-align: middle;
            padding: 12px 15px;
        }
        
        .btn-action {
            padding: 6px 12px;
            font-size: 0.85rem;
            margin: 0 3px;
            transition: all 0.2s;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        
        .btn-add {
            background-color: var(--secondary);
            border: none;
        }
        
        .btn-add:hover {
            background-color: #3d8b40;
        }
        
        .btn-back {
            background-color: #6c757d;
            border: none;
        }
        
        .btn-back:hover {
            background-color: #5a6268;
        }
        
        .empty-state {
            padding: 40px 0;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-header">
                    <i class="fas fa-users me-2"></i>User Management
                </h1>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-12">
                <a href="index.php?content=create&table=user" class="btn btn-add btn-success">
                    <i class="fas fa-plus me-1"></i> Add New User
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th width="25%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($result) > 0): ?>
                                    <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td class="text-center">
                                                <a href="index.php?content=edit&table=user&id=<?= $row['id'] ?>" 
                                                   class="btn btn-primary btn-action">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <a href="delete.php?table=user&id=<?= $row['id'] ?>" 
                                                   class="btn btn-danger btn-action"
                                                   onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $i++; endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            <i class="fas fa-user-slash fa-2x mb-3"></i>
                                            <p class="mb-0">No users found</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>