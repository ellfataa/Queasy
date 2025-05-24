<?php
if(isset($_GET["quiz_id"]) && $_GET["quiz_id"] !== ""){
    $quiz_id = $_GET["quiz_id"];
    $quiz_name = $_GET["quiz_name"];
    $result = mysqli_query($mysqli, "SELECT * FROM questions WHERE quiz_id = $quiz_id"); 
} else {
    $quiz_name = "All";
    $result = mysqli_query($mysqli, "SELECT * FROM questions");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Questions</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"
    crossorigin="anonymous"
  />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            padding: 8px 15px;
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
            margin-bottom: 20px;
        }
        
        .btn-back:hover {
            background-color: #5a6268;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
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
          <i class="fas fa-question-circle me-2"></i> Questions Table
        </h1>
        <p class="fw-medium">Quiz: <strong><?= htmlspecialchars($quiz_name) ?></strong></p>
        <a href="index.php?content=create&table=questions&quiz_id=<?= $quiz_id ?>&quiz_name=<?= urlencode($quiz_name) ?>" class="btn btn-create mb-3">
          <i class="fas fa-plus"></i> Create New Question
        </a>
      </div>
    </div>
    <?php if (isset($_GET['success']) && $_GET['success'] == 'delete'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <div class="table-container">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th>Question</th>
                  <th width="35%" class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                      <tr>
                        <td><?= $i ?></td>
                        <td><?= htmlspecialchars($row["quest_text"]) ?></td>
                        <td class="text-center">
                          <a href="index.php?content=edit&table=question&id=<?= $row['id'] ?>&quest_text=<?= urlencode($row['quest_text']) ?>&quiz_id=<?= $quiz_id ?>&quiz_name=<?= urlencode($quiz_name) ?>" class="btn btn-edit mt-1 w-50">
                            <i class="fas fa-edit me-1"></i> Edit
                          </a>
                          <a href="index.php?content=options&question_id=<?= $row['id'] ?>&question_text=<?= urlencode($row['quest_text']) ?>" class="btn btn-manage mt-1 w-50">
                            <i class="fas fa-cog me-1"></i> Manage
                          </a>
                          <a href="delete.php?table=questions&id=<?= $row['id'] ?>&quiz_id=<?= $quiz_id ?>&quiz_name=<?= urlencode($quiz_name) ?>" class="btn btn-delete mt-1 w-50" onclick="return confirm('Are you sure you want to delete this question?');">
                            <i class="fas fa-trash me-1"></i> Delete
                          </a>
                        </td>
                      </tr>
                      <?php $i++; endwhile; ?>
                <?php else: ?>
                    <tr>
                      <td colspan="3" class="text-center py-4 text-muted">
                        <i class="fas fa-exclamation-circle me-2"></i> No questions found
                      </td>
                    </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <a href="index.php" class="btn btn-back">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
      </div>
    </div>
  </div>
</body>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>
