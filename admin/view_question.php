<?php
// Add database connection at the top
session_start();
require_once("../layout/functions.php"); // This should include the $mysqli connection

// Add authentication checks
if(!isset($_SESSION["login"])){
    header("Location: ../login.php");
    exit;
}
if(!isset($_SESSION["admin"])){
    header("Location: ../index.php");
    exit;
}

if(isset($_GET["quiz_id"]) && $_GET["quiz_id"] !== ""){
    $quiz_id = $_GET["quiz_id"];
    $quiz_name = $_GET["quiz_name"];
    $result = mysqli_query($mysqli, "SELECT * FROM questions WHERE quiz_id = $quiz_id"); 
} else {
    $quiz_name = "All";
    $result = mysqli_query($mysqli, "SELECT * FROM questions");
}
?>

<style>
    .content-header {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .content-title {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
    }
    
    .content-title i {
        margin-right: 12px;
        font-size: 1.6rem;
    }
    
    .quiz-info {
        color: #6c757d;
        margin-bottom: 20px;
        font-size: 1.1rem;
    }
    
    .quiz-info strong {
        color: var(--dark);
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .btn-create {
        background: linear-gradient(135deg, var(--secondary), #45a049);
        border: none;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
    }
    
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        color: white;
    }
    
    .btn-create i {
        margin-right: 8px;
    }
    
    .table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 25px;
    }
    
    .table-header {
        background: linear-gradient(135deg, var(--primary), #5a4cb3);
        color: white;
        padding: 20px 25px;
        border-bottom: none;
    }
    
    .table-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .table-header i {
        margin-right: 10px;
    }
    
    .table {
        margin: 0;
        border: none;
    }
    
    .table th {
        background-color: #f8f9fa;
        color: var(--dark);
        font-weight: 600;
        border: none;
        padding: 15px 20px;
        font-size: 0.95rem;
    }
    
    .table td {
        padding: 15px 20px;
        border: none;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateX(3px);
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .row-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), #5a4cb3);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .question-text {
        font-size: 1rem;
        color: var(--dark);
        line-height: 1.5;
        max-width: 500px;
    }
    
    .action-buttons-table {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 120px;
    }
    
    .btn-action {
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
        box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
        color: white;
    }
    
    .btn-manage {
        background: linear-gradient(135deg, var(--secondary), #45a049);
        color: white;
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    }
    
    .btn-manage:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }
    
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        color: white;
    }
    
    .btn-action i {
        margin-right: 6px;
        font-size: 0.8rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .empty-state h5 {
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .empty-state p {
        margin: 0;
        font-size: 1rem;
    }
    
    .back-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
    }
    
    .btn-back {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(108, 117, 125, 0.3);
        border: none;
    }
    
    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        color: white;
    }
    
    .btn-back i {
        margin-right: 8px;
    }
    
    .success-alert {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: 1px solid #b8dacd;
        color: #155724;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.1);
    }
    
    .success-alert i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    
    .alert-dismissible .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #155724;
        opacity: 0.7;
        margin-left: auto;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .alert-dismissible .btn-close:hover {
        opacity: 1;
    }
    
    /* CSS Variables for consistent theming */
    :root {
        --primary: #6a5acd;
        --secondary: #4CAF50;
        --dark: #343a40;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .content-title {
            font-size: 1.5rem;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-create {
            justify-content: center;
        }
        
        .table-container {
            border-radius: 10px;
        }
        
        .table th,
        .table td {
            padding: 12px 15px;
        }
        
        .action-buttons-table {
            min-width: 100px;
        }
        
        .btn-action {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        .question-text {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.85rem;
        }
        
        .row-number {
            width: 35px;
            height: 35px;
            font-size: 0.8rem;
        }
        
        .back-section {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }
    }
</style>

<div class="content-header">
    <h2 class="content-title">
        <i class="fas fa-question-circle"></i>
        Questions Management
    </h2>
    <p class="quiz-info">
        Quiz: <strong><?= htmlspecialchars($quiz_name) ?></strong>
    </p>
    <div class="action-buttons">
        <a href="index.php?content=create&table=questions&quiz_id=<?= isset($quiz_id) ? $quiz_id : '' ?>&quiz_name=<?= urlencode($quiz_name) ?>" class="btn-create">
            <i class="fas fa-plus"></i>
            Create New Question
        </a>
    </div>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] == 'delete'): ?>
    <div class="success-alert alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        Question has been successfully deleted!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
<?php endif; ?>

<div class="table-container">
    <div class="table-header">
        <h5>
            <i class="fas fa-list"></i>
            Questions List
        </h5>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="80">No.</th>
                    <th>Question Text</th>
                    <th width="200" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <div class="row-number"><?= $i ?></div>
                            </td>
                            <td>
                                <div class="question-text">
                                    <?= htmlspecialchars($row["quest_text"]) ?>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons-table">
                                    <a href="index.php?content=edit&table=question&id=<?= $row['id'] ?>&quest_text=<?= urlencode($row['quest_text']) ?>&quiz_id=<?= isset($quiz_id) ? $quiz_id : '' ?>&quiz_name=<?= urlencode($quiz_name) ?>" 
                                       class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?content=options&question_id=<?= $row['id'] ?>&question_text=<?= urlencode($row['quest_text']) ?>" 
                                       class="btn-action btn-manage">Manage Options
                                    </a>
                                    <a href="delete.php?table=questions&id=<?= $row['id'] ?>&quiz_id=<?= isset($quiz_id) ? $quiz_id : '' ?>&quiz_name=<?= urlencode($quiz_name) ?>" 
                                       class="btn-action btn-delete" 
                                       onclick="return confirm('Are you sure you want to delete this question? This action cannot be undone.');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php $i++; endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <i class="fas fa-question-circle"></i>
                                <h5>No Questions Found</h5>
                                <p>There are no questions available for this quiz. Create your first question to get started!</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Auto-hide success alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.success-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                alert.classList.remove('show');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 5000);
    });
    
    // Smooth transitions for table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 50}ms`;
        row.classList.add('fade-in-row');
    });
});

// Add CSS animation for table rows
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInRow {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in-row {
        animation: fadeInRow 0.3s ease forwards;
    }
`;
document.head.appendChild(style);
</script>