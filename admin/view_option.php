<?php
    $quest_id = $_GET["question_id"] ?? "";
    $quest_text = $_GET["question_text"] ?? "All";
    
    // Pagination settings
    $items_per_page = 10;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;
    
    if($quest_id != ""){
        // Count total records
        $count_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM options WHERE question_id = $quest_id");
        $count_result = mysqli_fetch_array($count_query);
        $total_records = $count_result['total'];
        
        // Get paginated results
        $result = mysqli_query($mysqli, "SELECT * FROM options WHERE question_id = $quest_id LIMIT $items_per_page OFFSET $offset");
    } else {
        // Count total records
        $count_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM options");
        $count_result = mysqli_fetch_array($count_query);
        $total_records = $count_result['total'];
        
        // Get paginated results
        $result = mysqli_query($mysqli, "SELECT * FROM options LIMIT $items_per_page OFFSET $offset");
    }

    $total_pages = ceil($total_records / $items_per_page);
?>

<style>
    .options-management {
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

    .question-info {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary);
    }

    .question-info .label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .question-info .value {
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

    .option-content {
        min-width: 200px;
    }

    .option-text {
        font-weight: 500;
        color: var(--dark);
        font-size: 0.95rem;
        line-height: 1.4;
    }

    .correct-badge {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .incorrect-badge {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 5px;
        min-width: 100px;
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

    .option-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .option-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .option-card-header {
        margin-bottom: 15px;
    }

    .option-card-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .option-card-info > div {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .option-card-info .label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
    }

    .option-card-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .option-card-actions .action-btn {
        padding: 10px;
        font-size: 0.85rem;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .table thead th,
        .table tbody td {
            padding: 12px 8px;
            font-size: 0.85rem;
        }
        
        .option-text {
            font-size: 0.9rem;
        }
        
        .action-btn {
            font-size: 0.75rem;
            padding: 5px 8px;
        }
        
        .correct-badge,
        .incorrect-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
        }
    }

    @media (max-width: 768px) {
        .options-management {
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
        
        .question-info {
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
        .options-management {
            margin: 5px;
            padding: 12px;
        }
        
        .option-card {
            padding: 15px;
        }
        
        .option-card-info {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .option-card-actions {
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
        
        .options-management {
            box-shadow: none;
            padding: 0;
        }
        
        .table {
            font-size: 0.8rem;
        }
    }
</style>

<div class="options-management">
    <div class="page-header">
        <i class="fas fa-list-ul"></i><h2>Option Management</h2>
    </div>
    
    <div class="question-info">
        <div class="label">Selected Question:</div>
        <div class="value"><?= htmlspecialchars($quest_text) ?></div>
    </div>
    
    <a href="?content=create&table=options&quest_id=<?= $quest_id ?>&quest_text=<?= urlencode($quest_text) ?>" class="btn-create">
        <i class="fas fa-plus"></i>
        Create New Option
    </a>
    
    <!-- Desktop Table View -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="50%">Option Text</th>
                        <th width="15%">Is Correct</th>
                        <th width="20%" class="text-center">Actions</th>
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
                            <td class="option-content">
                                <div class="option-text"><?= htmlspecialchars($row["option_text"]) ?></div>
                            </td>
                            <td class="text-center">
                                <?php if($row['is_answer'] == 1): ?>
                                    <span class="correct-badge">
                                        <i class="fas fa-check"></i>True
                                    </span>
                                <?php else: ?>
                                    <span class="incorrect-badge">
                                        <i class="fas fa-times"></i>False
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?content=edit&table=option&id=<?= $row['id'] ?>&option_text=<?= urlencode($row['option_text']) ?>&question_id=<?= $quest_id ?>&question_text=<?= urlencode($quest_text) ?>" 
                                       class="btn-edit action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete.php?table=options&id=<?= $row['id'] ?>&question_id=<?= $quest_id ?>&question_text=<?= urlencode($quest_text) ?>" 
                                       class="btn-delete action-btn" 
                                       onclick="return confirm('Are you sure you want to delete this option? This action cannot be undone.');">
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
                            <td colspan="4">
                                <div class="no-data">
                                    <i class="fas fa-inbox"></i>
                                    <h5>No Options Found</h5>
                                    <p>There are no options for this question yet. Create your first option to get started!</p>
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
        if($quest_id != ""){
            $result = mysqli_query($mysqli, "SELECT * FROM options WHERE question_id = $quest_id LIMIT $items_per_page OFFSET $offset");
        } else {
            $result = mysqli_query($mysqli, "SELECT * FROM options LIMIT $items_per_page OFFSET $offset");
        }
        
        if($result && mysqli_num_rows($result) > 0):
            $counter = ($current_page - 1) * $items_per_page + 1;
            while($row = mysqli_fetch_array($result)):
        ?>
            <div class="option-card">
                <div class="option-card-header">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <strong style="font-size: 0.8rem; color: #6c757d;">#<?= $counter ?></strong>
                    </div>
                    <div class="option-text"><?= htmlspecialchars($row["option_text"]) ?></div>
                </div>
                
                <div class="option-card-info">
                    <div>
                        <div class="label">Correct Answer</div>
                        <?php if($row['is_answer'] == 1): ?>
                            <span class="correct-badge">
                                <i class="fas fa-check"></i>True
                            </span>
                        <?php else: ?>
                            <span class="incorrect-badge">
                                <i class="fas fa-times"></i>False
                            </span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="label">Status</div>
                        <div style="color: var(--primary); font-weight: 500;">Active</div>
                    </div>
                </div>
                
                <div class="option-card-actions">
                    <a href="?content=edit&table=option&id=<?= $row['id'] ?>&option_text=<?= urlencode($row['option_text']) ?>&question_id=<?= $quest_id ?>&question_text=<?= urlencode($quest_text) ?>" 
                       class="btn-edit action-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="delete.php?table=options&id=<?= $row['id'] ?>&question_id=<?= $quest_id ?>&question_text=<?= urlencode($quest_text) ?>" 
                       class="btn-delete action-btn" 
                       onclick="return confirm('Are you sure you want to delete this option? This action cannot be undone.');">
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
                <i class="fas fa-inbox"></i>
                <h5>No Options Found</h5>
                <p>There are no options for this question yet. Create your first option to get started!</p>
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