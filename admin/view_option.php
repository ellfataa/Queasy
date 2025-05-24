<?php
    $quest_id = $_GET["question_id"] ?? "";
    $quest_text = $_GET["question_text"] ?? "All";
    
    if($quest_id != ""){
        $result = mysqli_query($mysqli, "SELECT * FROM options WHERE question_id = $quest_id");
    } else {
        $result = mysqli_query($mysqli, "SELECT * FROM options");
    }
?>

<div class="page-header-section">
    <h1 class="page-header">
        <i class="fas fa-list-ul me-2"></i> Option List
    </h1>
    
    <p class="fw-medium mb-3">For question: <strong><?= htmlspecialchars($quest_text) ?></strong></p>
    
    <div class="action-buttons mb-4">
        <a href="?content=create&table=options&quest_id=<?= $quest_id ?>&quest_text=<?= urlencode($quest_text) ?>" 
           class="btn btn-create">
            <i class="fas fa-plus me-1"></i> Create Option
        </a>
    </div>
</div>

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
                            <td class="fw-medium"><?= $i ?></td>
                            <td><?= htmlspecialchars($row['option_text']) ?></td>
                            <td>
                                <?php if($row['is_answer'] == 1): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>True
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times me-1"></i>False
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="?content=edit&table=option&id=<?= $row['id'] ?>&option_text=<?= urlencode($row['option_text']) ?>&question_id=<?= $quest_id ?>&question_text=<?= urlencode($quest_text) ?>" 
                                       class="btn btn-sm btn-outline-info" title="Edit Option">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?table=options&id=<?= $row['id'] ?>&question_id=<?= $quest_id ?>&question_text=<?= urlencode($quest_text) ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Delete Option"
                                       onclick="return confirm('Are you sure you want to delete this option?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php $i++; endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-exclamation-circle text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted">No options found</h5>
                                <p class="text-muted mb-3">There are no options available for this question.</p>
                                <a href="?content=create&table=options&quest_id=<?= $quest_id ?>&quest_text=<?= urlencode($quest_text) ?>" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create First Option
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Page Header Styling */
.page-header-section {
    margin-bottom: 30px;
}

.page-header {
    color: var(--primary);
    margin-bottom: 15px;
    font-weight: 600;
    font-size: 1.8rem;
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

/* Button Styling */
.btn-create {
    background: linear-gradient(135deg, var(--secondary), #45a049);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(76, 175, 80, 0.2);
}

.btn-create:hover {
    background: linear-gradient(135deg, #45a049, var(--secondary));
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    color: white;
}

.btn-back {
    background-color: #6c757d;
    border: 1px solid #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background-color: #5a6268;
    border-color: #5a6268;
    color: white;
    transform: translateY(-2px);
}

/* Table Container */
.table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    padding: 0;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: linear-gradient(135deg, var(--primary), #5a4cb3);
    color: white;
    border: none;
    padding: 15px 20px;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 15px 20px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
    transform: scale(1.01);
}

.table tbody tr:last-child td {
    border-bottom: none;
}

/* Badge Styling */
.badge {
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.badge.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268) !important;
}

/* Button Group */
.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
    transition: all 0.3s ease;
}

.btn-outline-info {
    border-color: var(--info);
    color: var(--info);
}

.btn-outline-info:hover {
    background-color: var(--info);
    border-color: var(--info);
    color: white;
    transform: translateY(-2px);
}

.btn-outline-danger {
    border-color: var(--danger);
    color: var(--danger);
}

.btn-outline-danger:hover {
    background-color: var(--danger);
    border-color: var(--danger);
    color: white;
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    padding: 40px 20px;
}

.empty-state h5 {
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-state p {
    font-size: 0.95rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        font-size: 1.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-create,
    .btn-back {
        width: 100%;
        text-align: center;
        margin-bottom: 10px;
    }
    
    .table-container {
        border-radius: 10px;
        margin: 0 -15px;
    }
    
    .table thead th,
    .table tbody td {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 2px 0;
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 4px 8px;
    }
    
    .empty-state {
        padding: 30px 15px;
    }
    
    .empty-state i {
        font-size: 2rem !important;
    }
}

/* Animation for table rows */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.table tbody tr {
    animation: fadeInUp 0.3s ease forwards;
}

.table tbody tr:nth-child(1) { animation-delay: 0.1s; }
.table tbody tr:nth-child(2) { animation-delay: 0.2s; }
.table tbody tr:nth-child(3) { animation-delay: 0.3s; }
.table tbody tr:nth-child(4) { animation-delay: 0.4s; }
.table tbody tr:nth-child(5) { animation-delay: 0.5s; }

/* Loading state for buttons */
.btn-create:active,
.btn-back:active {
    transform: translateY(0);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to action buttons
    const actionButtons = document.querySelectorAll('.btn-create, .btn-back');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon) {
                const originalClass = icon.className;
                icon.className = 'fas fa-spinner fa-spin me-1';
                
                // Restore original icon after navigation
                setTimeout(() => {
                    icon.className = originalClass;
                }, 2000);
            }
        });
    });
    
    // Enhanced delete confirmation
    const deleteButtons = document.querySelectorAll('[onclick*="confirm"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const result = confirm('⚠️ Are you sure you want to delete this option?\n\nThis action cannot be undone!');
            
            if (result) {
                // Add loading state
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-spinner fa-spin';
                }
                
                // Navigate to delete URL
                window.location.href = this.href;
            }
        });
        
        // Remove the inline onclick
        button.removeAttribute('onclick');
    });
    
    // Table row hover effects
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 15px rgba(106, 90, 205, 0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
});
</script>