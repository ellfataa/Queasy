<?php
// Get user dari database
$result = mysqli_query($mysqli, "SELECT * FROM user");
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="page-header mb-1">
                        <i class="fas fa-users me-2"></i>User Management
                    </h2>
                    <p class="text-muted mb-0">Manage all users in the system</p>
                </div>
                <a href="?content=create&table=user" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Add New User
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th width="20%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && mysqli_num_rows($result) > 0): ?>
                                    <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td class="text-center"><?= $i ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?= htmlspecialchars($row['username']) ?></strong>
                                                        <?php if(isset($row['admin']) && $row['admin']): ?>
                                                            <span class="badge bg-danger ms-1">Admin</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($row['email'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="?content=edit&table=user&id=<?= $row['id'] ?>" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Edit User">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="delete.php?table=user&id=<?= $row['id'] ?>" 
                                                       class="btn btn-outline-danger btn-sm"
                                                       title="Delete User"
                                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $i++; endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No Users Found</h5>
                                                <p class="text-muted mb-3">There are no users in the system yet.</p>
                                                <a href="?content=create&table=user" class="btn btn-primary">
                                                    <i class="fas fa-plus me-1"></i> Add First User
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional styles for user management */
.page-header {
    color: var(--primary);
    font-weight: 600;
}

.avatar-sm {
    width: 35px;
    height: 35px;
    font-size: 0.85rem;
}

.empty-state {
    padding: 40px 20px;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.table th {
    font-weight: 500;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

.table td {
    vertical-align: middle;
    padding: 15px 12px;
}

.btn-group .btn {
    margin: 0 2px;
}

.badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.table-hover tbody tr:hover {
    background-color: rgba(106, 90, 205, 0.05);
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 1px 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to action buttons
    const actionButtons = document.querySelectorAll('.btn-outline-primary, .btn-outline-danger');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.classList.contains('btn-outline-danger')) {
                const icon = this.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'fas fa-spinner fa-spin';
                
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
            
            const username = this.closest('tr').querySelector('strong').textContent;
            const confirmMessage = `Are you sure you want to delete user "${username}"?\n\nThis action cannot be undone.`;
            
            if (confirm(confirmMessage)) {
                window.location.href = this.href;
            }
        });
        
        // Remove the inline onclick
        button.removeAttribute('onclick');
    });
});
</script>