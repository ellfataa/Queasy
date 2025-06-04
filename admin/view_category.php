<?php
// Pagination settings
$items_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Count total records
$count_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM category");
$count_result = mysqli_fetch_array($count_query);
$total_records = $count_result['total'];

// Get paginated results
$result = mysqli_query($mysqli, "SELECT * FROM category ORDER BY id ASC LIMIT $items_per_page OFFSET $offset");

$total_pages = ceil($total_records / $items_per_page);
?>

<style>
    .category-management {
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

    .stats-info {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .stats-info .total-records {
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stats-info .total-records i {
        color: var(--primary);
    }

    .stats-info .pagination-info {
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

    .row-number {
        font-weight: 600;
        color: var(--primary);
        background: rgba(106, 90, 205, 0.1);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        margin: 0 auto;
    }

    .category-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.95rem;
        line-height: 1.3;
    }

    .category-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        display: block;
        margin: 0 auto;
    }

    .category-img:hover {
        transform: scale(1.05);
        border-color: var(--primary);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 6px 12px;
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
        min-width: 70px;
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

    /* Mobile Card Layout */
    .mobile-cards {
        display: none;
    }

    .category-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .category-card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .category-card-number {
        background: var(--primary);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .category-card-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        flex-shrink: 0;
    }

    .category-card-info {
        flex: 1;
        min-width: 0;
    }

    .category-card-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 1.1rem;
        margin-bottom: 5px;
        word-wrap: break-word;
    }

    .category-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 15px;
    }

    .category-card-actions .action-btn {
        padding: 12px;
        font-size: 0.9rem;
        min-width: auto;
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

    /* Responsive Design */
    @media (max-width: 992px) {
        .table thead th,
        .table tbody td {
            padding: 12px 8px;
            font-size: 0.85rem;
        }
        
        .category-img {
            width: 50px;
            height: 50px;
        }
        
        .action-btn {
            font-size: 0.75rem;
            padding: 5px 8px;
            min-width: 60px;
        }
    }

    @media (max-width: 768px) {
        .category-management {
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
        
        .stats-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 15px;
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
        .category-management {
            margin: 5px;
            padding: 12px;
        }
        
        .category-card {
            padding: 15px;
        }
        
        .category-card-header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        
        .category-card-actions {
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
        .pagination-container,
        .header-actions {
            display: none;
        }
        
        .category-management {
            box-shadow: none;
            padding: 0;
        }
        
        .table {
            font-size: 0.8rem;
        }
    }
</style>

<div class="category-management">
    <div class="page-header">
        <i class="fas fa-tags"></i>
        <h2>Category Management</h2>
    </div>
    
    <div class="stats-info">
        <div class="total-records">
            <i class="fas fa-database"></i>
            <span>Total: <?= $total_records ?> categor<?= $total_records != 1 ? 'ies' : 'y' ?></span>
        </div>
        <?php if($total_pages > 1): ?>
        <div class="pagination-info">
            Page <?= $current_page ?> of <?= $total_pages ?>
        </div>
        <?php endif; ?>
    </div>
    
    <a href="index.php?content=create&table=category" class="btn-create">
        <i class="fas fa-plus"></i>
        Create New Category
    </a>
    
    <!-- Desktop Table View -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="8%">No.</th>
                        <th width="35%">Category Name</th>
                        <th width="20%">Image</th>
                        <th width="37%" class="text-center">Actions</th>
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
                                <div class="row-number"><?= $counter ?></div>
                            </td>
                            <td>
                                <div class="category-name"><?= htmlspecialchars($row["category_name"]) ?></div>
                            </td>
                            <td class="text-center">
                                <img src="../img/<?= htmlspecialchars($row['img']) ?>" 
                                     alt="<?= htmlspecialchars($row['category_name']) ?>" 
                                     class="category-img"
                                     onerror="this.src='../img/other.jpg'">
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="index.php?content=edit&table=category&id=<?= $row['id'] ?>&category_name=<?= urlencode($row['category_name']) ?>&img=<?= urlencode($row['img']) ?>" 
                                       class="btn-edit action-btn"
                                       title="Edit Category">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?content=quiz&categ_id=<?= $row['id'] ?>&name=<?= urlencode($row['category_name']) ?>" 
                                       class="btn-manage action-btn"
                                       title="Manage Quizzes">
                                        <i class="fas fa-gamepad"></i> Manage
                                    </a>
                                    <a href="delete.php?table=category&id=<?= $row['id'] ?>" 
                                       class="btn-delete action-btn" 
                                       onclick="return confirm('Are you sure you want to delete this category? This will also delete all quizzes in this category!');"
                                       title="Delete Category">
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
                                    <i class="fas fa-tags"></i>
                                    <h5>No Categories Found</h5>
                                    <p>You haven't created any categories yet. Create your first category to get started!</p>
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
        $result = mysqli_query($mysqli, "SELECT * FROM category ORDER BY id ASC LIMIT $items_per_page OFFSET $offset");
        
        if($result && mysqli_num_rows($result) > 0):
            $counter = ($current_page - 1) * $items_per_page + 1;
            while($row = mysqli_fetch_array($result)):
        ?>
            <div class="category-card">
                <div class="category-card-header">
                    <div class="category-card-number"><?= $counter ?></div>
                    <img src="../img/<?= htmlspecialchars($row['img']) ?>" 
                         alt="<?= htmlspecialchars($row['category_name']) ?>" 
                         class="category-card-img"
                         onerror="this.src='../img/other.jpg'">
                    <div class="category-card-info">
                        <div class="category-card-name"><?= htmlspecialchars($row["category_name"]) ?></div>
                    </div>
                </div>
                
                <div class="category-card-actions">
                    <a href="index.php?content=edit&table=category&id=<?= $row['id'] ?>&category_name=<?= urlencode($row['category_name']) ?>&img=<?= urlencode($row['img']) ?>" 
                       class="btn-edit action-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="index.php?content=quiz&categ_id=<?= $row['id'] ?>&name=<?= urlencode($row['category_name']) ?>" 
                       class="btn-manage action-btn">
                        <i class="fas fa-gamepad"></i> Manage
                    </a>
                    <a href="delete.php?table=category&id=<?= $row['id'] ?>" 
                       class="btn-delete action-btn" 
                       onclick="return confirm('Are you sure you want to delete this category? This will also delete all quizzes in this category!');"
                       style="grid-column: 1 / -1;">
                        <i class="fas fa-trash"></i> Delete Category
                    </a>
                </div>
            </div>
        <?php
            $counter++;
            endwhile;
        else:
        ?>
            <div class="no-data">
                <i class="fas fa-tags"></i>
                <h5>No Categories Found</h5>
                <p>You haven't created any categories yet. Create your first category to get started!</p>
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
document.addEventListener('DOMContentLoaded', function() {
    // Image error handling
    const categoryImages = document.querySelectorAll('.category-img, .category-card-img');
    categoryImages.forEach(img => {
        img.addEventListener('error', function() {
            this.src = '../img/other.jpg';
            this.alt = 'Default Category Image';
        });
    });
    
    // Smooth hover effects for table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            if (!this.querySelector('.no-data')) {
                this.style.backgroundColor = '#f8f9fa';
            }
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Enhanced category image hover effect
    const categoryImgs = document.querySelectorAll('.category-img, .category-card-img');
    categoryImgs.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.borderColor = 'var(--primary)';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.borderColor = '#e9ecef';
        });
    });
    
    // Smooth page transitions
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add loading state
            this.style.opacity = '0.6';
            this.style.pointerEvents = 'none';
        });
    });
});
</script>