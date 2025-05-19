<?php
    session_start();
    require_once("../functions.php");
    if(!isset($_SESSION["login"])){
        header("Location: ../login.php");
        exit;
    }
    if(!isset($_SESSION["admin"])){
        header("Location: ../index.php");
        exit;
    }
    $result = mysqli_query($mysqli, "SELECT * FROM category");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"
      integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv"
      crossorigin="anonymous"
    />
    <title>User</title>

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
            padding: 20px;
        }
        
        .page-header {
            color: var(--primary);
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
        }
        
        .create-btn {
            background-color: var(--primary);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .create-btn:hover {
            background-color: #5a4cb3;
            transform: translateY(-2px);
            color: white;
        }
        
        .create-btn i {
            margin-right: 5px;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
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
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 0.85rem;
            margin: 0 3px;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        
        .btn-create {
            background-color: var(--secondary);
            border: none;
            color: white;
        }

        .btn-create:hover {
            background-color: #3d8b40;
            color: white;
        }

        .btn-edit {
            background-color: #17a2b8;
            border: none;
            color: white;
        }
        
        .btn-manage {
            background-color: var(--secondary);
            border: none;
            color: white;
        }
        
        .btn-back {
            background-color: #6c757d;
            border: none;
            color: white;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background-color: #5a6268;
            color: white;
        }
        
        .category-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-header">
                    <i class="fas fa-tags me-2"></i> Category Management
                </h1>
                <a href="index.php?content=create&table=category" class="btn btn-create">
                    <i class="fas fa-plus"></i> Create New Category
                </a>
                
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Category Name</th>
                                    <th width="15%">Image</th>
                                    <th width="25%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($result) > 0): ?>
                                    <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                                            <td>
                                                <img src="../img/<?= htmlspecialchars($row['img']) ?>" 
                                                     alt="<?= htmlspecialchars($row['category_name']) ?>" 
                                                     class="category-img">
                                            </td>
                                            <td class="text-center">
                                                <a href="index.php?content=edit&table=category&id=<?= $row['id'] ?>&category_name=<?= urlencode($row['category_name']) ?>&img=<?= $row['img'] ?>" 
                                                   class="btn btn-edit action-btn">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <a href="index.php?content=quiz&categ_id=<?= $row['id'] ?>&name=<?= urlencode($row['category_name']) ?>" 
                                                   class="btn btn-manage action-btn">
                                                    <i class="fas fa-cog me-1"></i> Manage
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $i++; endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-exclamation-circle me-2"></i> No categories found
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