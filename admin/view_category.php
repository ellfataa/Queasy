<?php
$result = mysqli_query($mysqli, "SELECT * FROM category ORDER BY id ASC");
?>

<style>
    .create-btn {
        background-color: var(--primary);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin-bottom: 25px;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .create-btn:hover {
        background-color: #5a4cb3;
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 4px 12px rgba(106, 90, 205, 0.3);
    }
    
    .create-btn i {
        margin-right: 8px;
    }
    
    .table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        padding: 25px;
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .table {
        border-radius: 10px;
        overflow: hidden;
        border: none;
        margin-bottom: 0;
    }
    
    .table th {
        background-color: var(--primary);
        color: white;
        font-weight: 500;
        border: none;
        padding: 15px;
        font-size: 0.95rem;
    }
    
    .table td {
        vertical-align: middle;
        padding: 15px;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9ff;
        transform: translateY(-1px);
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .action-btn {
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 0.85rem;
        margin: 0 3px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        border: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .action-btn i {
        margin-right: 5px;
    }

    .btn-edit {
        background-color: #17a2b8;
        color: white;
    }
    
    .btn-edit:hover {
        background-color: #138496;
        color: white;
    }
    
    .btn-manage {
        background-color: var(--secondary);
        color: white;
    }
    
    .btn-manage:hover {
        background-color: #3d8b40;
        color: white;
    }
    
    .category-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .category-img:hover {
        transform: scale(1.1);
        border-color: var(--primary);
    }
    
    .category-name {
        font-weight: 500;
        color: var(--dark);
        font-size: 1rem;
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
    
    .empty-state h4 {
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .empty-state p {
        margin-bottom: 25px;
    }
    
    .page-header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .page-header-title {
        color: var(--primary);
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }
    
    .page-header-title i {
        margin-right: 12px;
        font-size: 1.3rem;
    }
    
    .category-stats {
        display: flex;
        gap: 20px;
        align-items: center;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .stat-item i {
        color: var(--primary);
    }
    
    .row-number {
        font-weight: 600;
        color: var(--primary);
        background: rgba(106, 90, 205, 0.1);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }
    
    @media (max-width: 768px) {
        .page-header-section {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }
        
        .category-stats {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .table-container {
            overflow-x: auto;
            padding: 15px;
        }
        
        .table {
            min-width: 600px;
        }
        
        .action-btn {
            margin: 2px;
            font-size: 0.8rem;
            padding: 6px 10px;
        }
    }
</style>

<div class="category-management">
    <!-- Page Header -->
    <div class="page-header-section">
        <div>
            <h2 class="page-header-title">
                <i class="fas fa-tags"></i>
                Category Management  
            </h2>
        </div>
        <div class="category-stats">
            <div class="stat-item">
                <i class="fas fa-database"></i>
                <span><?= mysqli_num_rows($result) ?> Categories</span>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="actions-section">
        <a href="index.php?content=create&table=category" class="create-btn">
            <i class="fas fa-plus"></i>
            Create New Category
        </a>
    </div>
    
    <!-- Categories Table -->
    <div class="table-container">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="8%">No.</th>
                            <th width="40%">Category Name</th>
                            <th width="20%">Image</th>
                            <th width="32%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>
                                    <div class="row-number"><?= $i ?></div>
                                </td>
                                <td>
                                    <div class="category-name"><?= htmlspecialchars($row['category_name']) ?></div>
                                </td>
                                <td>
                                    <img src="../img/<?= htmlspecialchars($row['img']) ?>" 
                                         alt="<?= htmlspecialchars($row['category_name']) ?>" 
                                         class="category-img"
                                         onerror="this.src='../img/other.jpg'">
                                </td>
                                <td class="text-center">
                                    <a href="index.php?content=edit&table=category&id=<?= $row['id'] ?>&category_name=<?= urlencode($row['category_name']) ?>&img=<?= urlencode($row['img']) ?>" 
                                       class="btn btn-edit action-btn"
                                       title="Edit Category">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <a href="index.php?content=quiz&categ_id=<?= $row['id'] ?>&name=<?= urlencode($row['category_name']) ?>" 
                                       class="btn btn-manage action-btn"
                                       title="Manage Quizzes">
                                        <i class="fas fa-gamepad"></i>
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            <?php $i++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-tags"></i>
                <h4>No Categories Found</h4>
                <p>You haven't created any categories yet. Start by creating your first category.</p>
                <a href="index.php?content=create&table=category" class="create-btn">
                    <i class="fas fa-plus"></i>
                    Create First Category
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth hover effects
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9ff';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Image error handling
    const categoryImages = document.querySelectorAll('.category-img');
    categoryImages.forEach(img => {
        img.addEventListener('error', function() {
            this.src = '../img/default-category.png';
            this.alt = 'Default Category Image';
        });
    });
    
    // Confirm actions
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.textContent.trim().includes('Delete')) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                    window.location.href = this.href;
                }
            }
        });
    });
});
</script>