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

// Pagination settings
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

if(isset($_GET["quiz_id"]) && $_GET["quiz_id"] !== ""){
    $quiz_id = $_GET["quiz_id"];
    $quiz_name = $_GET["quiz_name"];
    
    // Count total records
    $count_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM questions WHERE quiz_id = $quiz_id");
    $count_result = mysqli_fetch_array($count_query);
    $total_records = $count_result['total'];
    
    // Get paginated results
    $result = mysqli_query($mysqli, "SELECT * FROM questions WHERE quiz_id = $quiz_id LIMIT $items_per_page OFFSET $offset"); 
} else {
    $quiz_name = "All";
    
    // Count total records
    $count_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM questions");
    $count_result = mysqli_fetch_array($count_query);
    $total_records = $count_result['total'];
    
    // Get paginated results
    $result = mysqli_query($mysqli, "SELECT * FROM questions LIMIT $items_per_page OFFSET $offset");
}

$total_pages = ceil($total_records / $items_per_page);
?>

<style>
    .questions-management {
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
        flex-wrap: wrap;
    }

    .page-header i {
        font-size: 1.5rem;
    }

    .page-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .header-actions {
        margin-left: auto;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .quiz-info {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary);
    }

    .quiz-info .label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .quiz-info .value {
        color: var(--primary);
        font-size: 1.1rem;
        font-weight: 500;
    }

    .stats-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #dee2e6;
    }

    .stats-info .total-records {
        font-size: 0.9rem;
        color: #6c757d;
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
        cursor: pointer;
    }
    
    .alert-dismissible .btn-close:hover {
        opacity: 1;
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
        font-size: 0.95rem;
    }

    .btn-create:hover {
        background: linear-gradient(135deg, #45a049, #3d8b40);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        text-decoration: none;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .table {
        margin-bottom: 0;
        width: 100%;
    }

    .table thead th {
        background: linear-gradient(135deg, var(--primary), #5a4fcf);
        color: white;
        font-weight: 600;
        border: none;
        padding: 15px 10px;
        font-size: 0.9rem;
        text-align: center;
        white-space: nowrap;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody td {
        padding: 15px 10px;
        vertical-align: middle;
        border-color: #eee;
    }

    .question-content {
        min-width: 300px;
    }

    .question-text {
        font-weight: 500;
        color: var(--dark);
        font-size: 0.95rem;
        line-height: 1.4;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 5px;
        min-width: 120px;
    }

    .action-btn {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        border: none;
        white-space: nowrap;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        text-decoration: none;
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

    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .no-data i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    /* Pagination Styles */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .pagination {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 5px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination li {
        margin: 0;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .pagination a:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        text-decoration: none;
    }

    .pagination .active span {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination .disabled span {
        color: #6c757d;
        cursor: not-allowed;
        background: #f8f9fa;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Mobile Card Layout */
    .mobile-cards {
        display: none;
    }

    .question-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .question-card-header {
        margin-bottom: 15px;
    }

    .question-card-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .question-card-info > div {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .question-card-info .label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
    }

    .question-card-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .question-card-actions .action-btn {
        padding: 10px;
        font-size: 0.85rem;
    }

    /* CSS Variables for consistent theming */
    :root {
        --primary: #6a5acd;
        --secondary: #4CAF50;
        --dark: #343a40;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .table thead th,
        .table tbody td {
            padding: 12px 8px;
            font-size: 0.85rem;
        }
        
        .question-text {
            font-size: 0.9rem;
        }
        
        .action-btn {
            font-size: 0.75rem;
            padding: 5px 8px;
        }
    }

    @media (max-width: 768px) {
        .questions-management {
            padding: 15px;
            margin: 10px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .page-header h2 {
            font-size: 1.3rem;
        }
        
        .header-actions {
            margin-left: 0;
            width: 100%;
        }
        
        .quiz-info {
            padding: 12px 15px;
        }
        
        .stats-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .btn-create {
            width: 100%;
            justify-content: center;
            padding: 15px;
            font-size: 1rem;
        }
        
        /* Hide table, show cards on mobile */
        .table-container {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .pagination {
            gap: 3px;
        }
        
        .pagination a,
        .pagination span {
            min-width: 35px;
            height: 35px;
            font-size: 0.8rem;
            padding: 0 8px;
        }
        
        .pagination-container {
            flex-direction: column;
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .questions-management {
            margin: 5px;
            padding: 12px;
        }
        
        .question-card {
            padding: 15px;
        }
        
        .question-card-info {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .question-card-actions {
            grid-template-columns: 1fr;
        }
        
        .pagination a,
        .pagination span {
            min-width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }
    }

    /* Print Styles */
    @media print {
        .btn-create,
        .action-buttons,
        .pagination-container {
            display: none;
        }
        
        .questions-management {
            box-shadow: none;
            padding: 0;
        }
        
        .table {
            font-size: 0.8rem;
        }
    }
</style>

<div class="questions-management">
    <div class="page-header">
        <i class="fas fa-question-circle"></i><h2>Questions Management</h2>
    </div>
    
    <div class="quiz-info">
        <div class="label">Selected Quiz:</div>
        <div class="value"><?= htmlspecialchars($quiz_name) ?></div>
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
    
    <a href="index.php?content=create&table=questions&quiz_id=<?= isset($quiz_id) ? $quiz_id : '' ?>&quiz_name=<?= urlencode($quiz_name) ?>" class="btn-create">
        <i class="fas fa-plus"></i>
        Create New Question
    </a>
    
    <!-- Desktop Table View -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="60%">Question Text</th>
                        <th width="25%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($result && mysqli_num_rows($result) > 0):
                        $counter = ($current_page - 1) * $items_per_page + 1;
                        while($row = mysqli_fetch_array($result)):
                    ?>
                        <tr>
                            <td class="text-center">
                                <strong><?= $counter ?></strong>
                            </td>
                            <td class="question-content">
                                <div class="question-text"><?= htmlspecialchars($row["quest_text"]) ?></div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="index.php?content=edit&table=question&id=<?= $row['id'] ?>&quest_text=<?= urlencode($row['quest_text']) ?>&quiz_id=<?= isset($quiz_id) ? $quiz_id : '' ?>&quiz_name=<?= urlencode($quiz_name) ?>" 
                                       class="btn-edit action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?content=options&question_id=<?= $row['id'] ?>&question_text=<?= urlencode($row['quest_text']) ?>" 
                                       class="btn-manage action-btn">
                                        <i class="fas fa-cog"></i> Manage
                                    </a>
                                    <a href="delete.php?table=questions&id=<?= $row['id'] ?>&quiz_id=<?= isset($quiz_id) ? $quiz_id : '' ?>&quiz_name=<?= urlencode($quiz_name) ?>" 
                                       class="btn-delete action-btn" 
                                       onclick="return confirm('Are you sure you want to delete this question? This action cannot be undone.');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $counter++;
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="3">
                                <div class="no-data">
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
    
    <!-- Mobile Card View -->
    <div class="mobile-cards">
        <?php
        // Reset result for mobile cards
        if(isset($_GET["quiz_id"]) && $_GET["quiz_id"] !== ""){
            $result = mysqli_query($mysqli, "SELECT * FROM questions WHERE quiz_id = $quiz_id LIMIT $items_per_page OFFSET $offset");
        } else {
            $result = mysqli_query($mysqli, "SELECT * FROM questions LIMIT $items_per_page OFFSET $offset");
        }
        
        if($result && mysqli_num_rows($result) > 0):
            $counter = ($current_page - 1) * $items_per_page + 1;
            while($row = mysqli_fetch_array($result)):
        ?>
            <div class="question-card">
                <div class="question-card-header">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <strong style="font-size: 0.8rem; color: #6c757d;">#<?= $counter ?></strong>
                    </div>
                    <div class="question-text"><?= htmlspecialchars($row["quest_text"]) ?></div>
                </div>
                <div class="question-card-actions">
                    <a href="index.php?content=edit&table=question&id=<?= $row['id'] ?>&quest_text=<?= urlencode($row['quest_text']) ?>&quiz_id=<?= isset($quiz_id) ? $quiz_id : '' ?>&quiz_name=<?= urlencode($quiz_name) ?>" 
                       class="btn-edit action-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="index.php?content=options&question_id=<?= $row['id'] ?>&question_text=<?= urlencode($row['quest_text']) ?>" 
                       class="btn-manage action-btn">
                        <i class="fas fa-cog"></i> Options
                    </a>
                    <a href="delete.php?table=questions&id=<?= $row['id'] ?>&quiz_id=<?= isset($quiz_id) ? $quiz_id : '' ?>&quiz_name=<?= urlencode($quiz_name) ?>" 
                       class="btn-delete action-btn" 
                       onclick="return confirm('Are you sure you want to delete this question? This action cannot be undone.');">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        <?php
            $counter++;
            endwhile;
        else:
        ?>
            <div class="no-data">
                <i class="fas fa-question-circle"></i>
                <h5>No Questions Found</h5>
                <p>There are no questions available for this quiz. Create your first question to get started!</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if($total_pages > 1): ?>
    <div class="pagination-container">
        <div class="pagination-info">
            Showing <?= ($current_page - 1) * $items_per_page + 1 ?> to <?= min($current_page * $items_per_page, $total_records) ?> 
            of <?= $total_records ?> results
        </div>
        
        <ul class="pagination">
            <!-- First Page -->
            <?php if($current_page > 1): ?>
                <li>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Previous Page -->
            <?php if($current_page > 1): ?>
                <li>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $current_page - 1])) ?>">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </li>
            <?php else: ?>
                <li class="disabled">
                    <span><i class="fas fa-angle-left"></i></span>
                </li>
            <?php endif; ?>
            
            <!-- Page Numbers -->
            <?php
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            
            if($start_page > 1): ?>
                <li><a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a></li>
                <?php if($start_page > 2): ?>
                    <li class="disabled"><span>...</span></li>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                <li <?= $i == $current_page ? 'class="active"' : '' ?>>
                    <?php if($i == $current_page): ?>
                        <span><?= $i ?></span>
                    <?php else: ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                    <?php endif; ?>
                </li>
            <?php endfor; ?>
            
            <?php if($end_page < $total_pages): ?>
                <?php if($end_page < $total_pages - 1): ?>
                    <li class="disabled"><span>...</span></li>
                <?php endif; ?>
                <li><a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>"><?= $total_pages ?></a></li>
            <?php endif; ?>
            
            <!-- Next Page -->
            <?php if($current_page < $total_pages): ?>
                <li>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $current_page + 1])) ?>">
                        <i class="fas fa-angle-right"></i>
                    </a>
                </li>
            <?php else: ?>
                <li class="disabled">
                    <span><i class="fas fa-angle-right"></i></span>
                </li>
            <?php endif; ?>
            
            <!-- Last Page -->
            <?php if($current_page < $total_pages): ?>
                <li>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
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
    
    // Handle alert close button
    document.querySelectorAll('.btn-close').forEach(button => {
        button.addEventListener('click', function() {
            const alert = this.closest('.success-alert');
            alert.classList.remove('show');
            setTimeout(() => {
                alert.remove();
            }, 300);
        });
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