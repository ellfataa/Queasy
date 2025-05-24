<?php
    $quest_id = $_GET["question_id"] ?? "";
    $quest_text = $_GET["question_text"] ?? "All";
    
    if($quest_id != ""){
        $result = mysqli_query($mysqli, "SELECT * FROM options WHERE question_id = $quest_id");
    } else {
        $result = mysqli_query($mysqli, "SELECT * FROM options");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"
    integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv"
    crossorigin="anonymous"
  />
  <title>Options</title>
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
        padding: 8px 15px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        margin-bottom: 20px;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        background-color: #5a4cb3;
        color: white;
        transform: translateY(-2px);
    }

    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        padding: 20px;
    }

    .table th {
        background-color: var(--primary);
        color: white;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 0.85rem;
        margin: 0 3px;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
    }

    .btn-edit {
        background-color: #17a2b8;
    }

    .btn-edit:hover {
        background-color: #138496;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        margin-top: 20px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;

    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <h1 class="page-header">
        <i class="fas fa-list-ul me-2"></i> Option List
    </h1>
    
    <p class="fw-medium mb-2">For question: <strong><?= htmlspecialchars($quest_text) ?></strong></p>
    
    <a href="index.php?content=create&table=options&quest_id=<?= $quest_id ?>&quest_text=<?= urlencode($quest_text) ?>" 
       class="btn-create">
        <i class="fas fa-plus"></i> Create Option
    </a>

    <div class="table-container">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="5%">No.</th>
              <th>Option Text</th>
              <th width="15%">Is Correct</th>
              <th width="25%" class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= htmlspecialchars($row['option_text']) ?></td>
                        <td><?= $row['is_answer'] == 1 ? "True" : "False" ?></td>
                        <td class="text-center">
                            <a href="index.php?content=edit&table=option&id=<?= $row['id'] ?>&option_text=<?= urlencode($row['option_text']) ?>&question_id=<?= $quest_id ?>&question_text=<?= urlencode($quest_text) ?>" 
                               class="btn-edit action-btn">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="delete.php?table=options&id=<?= $row['id'] ?>&question_id=<?= $quest_id ?>&question_text=<?= urlencode($quest_text) ?>" 
                               class="btn-delete action-btn" 
                               onclick="return confirm('Are you sure you want to delete this option?');">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php $i++; endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        <i class="fas fa-exclamation-circle me-2"></i> No options found
                    </td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <a href="index.php">
        <button class="btn-back">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </button>
    </a>
  </div>

  <!-- Font Awesome (for icons) -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
