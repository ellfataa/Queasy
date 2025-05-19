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
    if(isset($_GET["categ_id"]) && $_GET["categ_id"] != ""){
        $categ_id = $_GET["categ_id"];
        $categ_name = $_GET["name"];
        $result = mysqli_query($mysqli, "SELECT * FROM quizzes WHERE category_id = $categ_id"); 
    } else {
        $categ_name = "All";
        $result = mysqli_query($mysqli, "SELECT * FROM quizzes");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv" crossorigin="anonymous">
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

        .btn-create {
            background-color: var(--secondary);
            border: none;
            color: white;
            border-radius: 5px;
            padding: 8px 15px;
            margin-bottom: 20px;
        }

        .btn-create:hover {
            background-color: #3d8b40;
            color: white;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }

        .table th {
            background-color: var(--primary);
            color: white;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 0.85rem;
            margin: 3px;
            transition: all 0.3s;
            display: inline-block;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .btn-edit {
            background-color: #17a2b8;
            color: white;
            border: none;
        }

        .btn-manage {
            background-color: var(--secondary);
            color: white;
            border: none;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="page-header"><i class="fas fa-question-circle me-2"></i> Quiz Management</h1>
            <p class="fw-medium">Category: <?= htmlspecialchars($categ_name) ?></p>
            <a href="index.php?content=create&table=quizzes&categ_id=<?= $categ_id ?>&categ_name=<?= urlencode($categ_name) ?>" class="btn btn-create">
                <i class="fas fa-plus me-1"></i> Create New Quiz
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
                                <th>No.</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Creator</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                while($row = mysqli_fetch_array($result)):
                                    echo "<tr>";
                                    echo "<td>{$i}</td>";
                                    echo "<td>".htmlspecialchars($row["title"])."</td>";
                                    // Tampilkan hanya sebagian deskripsi (misal 50 karakter pertama)
                                    $desc = htmlspecialchars($row["description"]);
                                    if(strlen($desc) > 50){
                                        $desc = substr($desc, 0, 50) . "...";
                                    }
                                    echo "<td>{$desc}</td>";

                                    // Get category name
                                    $category_query = mysqli_query($mysqli, "SELECT category_name FROM category WHERE id = ".$row["category_id"]);
                                    $category = mysqli_fetch_assoc($category_query);
                                    echo "<td>".htmlspecialchars($category['category_name'] ?? 'Unknown')."</td>";

                                    // Get creator name
                                    $user_query = mysqli_query($mysqli, "SELECT username FROM user WHERE id = ".$row["creator_id"]);
                                    $user = mysqli_fetch_assoc($user_query);
                                    echo "<td>".htmlspecialchars($user['username'] ?? 'Unknown')."</td>";

                                    // Action buttons
                                    echo "<td class='text-center'>";
                                    echo "<a href='index.php?content=edit&table=quizzes&id={$row['id']}&title=".urlencode($row['title'])."&desc=".urlencode($row['description'])."&categ_id=$categ_id&categ_name=".urlencode($categ_name)."' class='btn btn-edit action-btn w-100'><i class='fas fa-edit me-1'></i>Edit</a>";
                                    echo "<a href='index.php?content=questions&quiz_id={$row['id']}&quiz_name=".urlencode($row['title'])."' class='btn btn-manage action-btn w-100'><i class='fas fa-cog me-1'></i>Manage</a>";
                                    echo "<a href='delete.php?table=quizzes&id={$row['id']}&categ_id=$categ_id&categ_name=".urlencode($categ_name)."' class='btn btn-delete action-btn w-100' onclick=\"return confirm('Are you sure?');\"><i class='fas fa-trash me-1'></i>Delete</a>";
                                    echo "</td>";

                                    echo "</tr>";
                                    $i++;
                                endwhile;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="index.php" class="btn btn-back"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
