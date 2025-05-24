<?php
// Validasi session sudah dihandle di index.php
if(isset($_GET["categ_id"]) && $_GET["categ_id"] != ""){
    $categ_id = $_GET["categ_id"];
    $categ_name = $_GET["name"];
    $result = mysqli_query($mysqli, "SELECT * FROM quizzes WHERE category_id = $categ_id"); 
} else {
    $categ_name = "All";
    $result = mysqli_query($mysqli, "SELECT * FROM quizzes");
}
?>

<style>
    .quiz-management {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .page-header {
        color: var(--primary);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-header i {
        font-size: 1.5rem;
    }

    .category-info {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary);
    }

    .category-info .label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .category-info .value {
        color: var(--primary);
        font-size: 1.1rem;
        font-weight: 500;
    }

    .btn-create {
        background: linear-gradient(135deg, var(--secondary), #45a049);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-create:hover {
        background: linear-gradient(135deg, #45a049, #3d8b40);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
    }

    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: linear-gradient(135deg, var(--primary), #5a4fcf);
        color: white;
        font-weight: 600;
        border: none;
        padding: 15px;
        font-size: 0.95rem;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateX(2px);
    }

    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-color: #eee;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 5px;
        min-width: 120px;
    }

    .action-btn {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border: none;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }

    .btn-edit {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #138496, #117a8b);
        color: white;
    }

    .btn-manage {
        background: linear-gradient(135deg, var(--secondary), #45a049);
        color: white;
    }

    .btn-manage:hover {
        background: linear-gradient(135deg, #45a049, #3d8b40);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        color: white;
    }

    .btn-back {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #5a6268, #495057);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }

    .quiz-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .quiz-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .category-badge {
        background: linear-gradient(135deg, var(--info), #117a8b);
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .creator-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--dark);
        font-weight: 500;
    }

    .creator-info i {
        color: var(--primary);
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .no-data i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .quiz-management {
            padding: 15px;
        }
        
        .action-buttons {
            flex-direction: row;
            flex-wrap: wrap;
            min-width: auto;
        }
        
        .action-btn {
            flex: 1;
            min-width: 80px;
            font-size: 0.8rem;
            padding: 6px 8px;
        }
        
        .table-responsive {
            font-size: 0.9rem;
        }
        
        .table thead th,
        .table tbody td {
            padding: 10px 8px;
        }
    }
</style>

<div class="quiz-management">
    <h2 class="page-header">
        <i class="fas fa-gamepad"></i>
        Quiz Management
    </h2>
    
    <div class="category-info">
        <div class="label">Selected Category:</div>
        <div class="value"><?= htmlspecialchars($categ_name) ?></div>
    </div>
    
    <a href="index.php?content=create&table=quizzes&categ_id=<?= $categ_id ?>&categ_name=<?= urlencode($categ_name) ?>" class="btn-create">
        <i class="fas fa-plus"></i>
        Create New Quiz
    </a>
    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="25%">Quiz Details</th>
                        <th width="15%">Category</th>
                        <th width="15%">Creator</th>
                        <th width="20%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($result && mysqli_num_rows($result) > 0):
                        $i = 1;
                        while($row = mysqli_fetch_array($result)):
                    ?>
                        <tr>
                            <td class="text-center">
                                <strong><?= $i ?></strong>
                            </td>
                            <td>
                                <div class="quiz-title"><?= htmlspecialchars($row["title"]) ?></div>
                                <div class="quiz-description">
                                    <?php
                                    $desc = htmlspecialchars($row["description"]);
                                    echo strlen($desc) > 80 ? substr($desc, 0, 80) . "..." : $desc;
                                    ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                // Get category name
                                $category_query = mysqli_query($mysqli, "SELECT category_name FROM category WHERE id = ".$row["category_id"]);
                                $category = mysqli_fetch_assoc($category_query);
                                ?>
                                <span class="category-badge">
                                    <?= htmlspecialchars($category['category_name'] ?? 'Unknown') ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                // Get creator name
                                $user_query = mysqli_query($mysqli, "SELECT username FROM user WHERE id = ".$row["creator_id"]);
                                $user = mysqli_fetch_assoc($user_query);
                                ?>
                                <div class="creator-info">
                                    <i class="fas fa-user"></i>
                                    <?= htmlspecialchars($user['username'] ?? 'Unknown') ?>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="index.php?content=edit&table=quizzes&id=<?= $row['id'] ?>&title=<?= urlencode($row['title']) ?>&desc=<?= urlencode($row['description']) ?>&categ_id=<?= $categ_id ?>&categ_name=<?= urlencode($categ_name) ?>" 
                                       class="btn-edit action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?content=questions&quiz_id=<?= $row['id'] ?>&quiz_name=<?= urlencode($row['title']) ?>" 
                                       class="btn-manage action-btn">
                                        <i class="fas fa-cog"></i> Manage
                                    </a>
                                    <a href="delete.php?table=quizzes&id=<?= $row['id'] ?>&categ_id=<?= $categ_id ?>&categ_name=<?= urlencode($categ_name) ?>" 
                                       class="btn-delete action-btn" 
                                       onclick="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $i++;
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="5">
                                <div class="no-data">
                                    <i class="fas fa-inbox"></i>
                                    <h5>No Quizzes Found</h5>
                                    <p>There are no quizzes in this category yet. Create your first quiz to get started!</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>