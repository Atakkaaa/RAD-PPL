<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Include database connection and functions
require_once '../config/database.php';
require_once '../config/init.php';

// Edit role if requested
if (isset($_POST['edit_role']) && isset($_POST['user_id']) && isset($_POST['new_role'])) {
    $userId = $_POST['user_id'];
    $newRole = $_POST['new_role'];
    
    // Validate role
    if (!in_array($newRole, ['admin', 'student'])) {
        $_SESSION['error_message'] = "Invalid role selected.";
    } elseif ($userId == $_SESSION['user_id']) {
        $_SESSION['error_message'] = "You cannot change your own role.";
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        // Get current user data for logging
        $query = "SELECT username, role FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $oldRole = $user['role'];
            
            // Update user role
            $query = "UPDATE users SET role = :role WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':role', $newRole);
            $stmt->bindParam(':id', $userId);
            
            if ($stmt->execute()) {
                // Log the activity
                logActivity($_SESSION['user_id'], 'Edit User Role', "Changed role of {$user['username']} from {$oldRole} to {$newRole}");
                
                $_SESSION['success_message'] = "User role updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update user role.";
            }
        } else {
            $_SESSION['error_message'] = "User not found.";
        }
    }
    
    // Redirect to remove POST data
    header("Location: all_users.php");
    exit();
}

// Delete user if requested
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $userId = $_GET['delete'];
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Get user username for logging
    $query = "SELECT username FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Check if user has associated student record
        $query = "SELECT id FROM students WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Begin transaction  
        $db->beginTransaction();
        
        try {
            // Delete student record if exists
            if ($student) {
                $query = "DELETE FROM students WHERE user_id = :user_id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $userId);
                $stmt->execute();
            }
            
            // Delete the user
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $userId);
            
            if ($stmt->execute()) {
                // Commit transaction
                $db->commit();
                
                // Log the activity
                logActivity($_SESSION['user_id'], 'Delete User', "Deleted user: {$user['username']}");
                
                // Set success message
                $_SESSION['success_message'] = "User deleted successfully.";
            } else {
                $db->rollback();
                $_SESSION['error_message'] = "Failed to delete user.";
            }
        } catch (Exception $e) {
            // Rollback transaction
            $db->rollback();
            $_SESSION['error_message'] = "Failed to delete user.";
        }
    } else {
        $_SESSION['error_message'] = "User not found.";
    }
    
    // Redirect to remove the delete parameter
    header("Location: all_users.php");
    exit();
}

// Get all users
$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, username, role, created_at
          FROM users 
          ORDER BY role ASC, username ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include_once '../includes/header.php';
?>
<body class="body-page">
<style>
.page-header {
    margin-top: 10px !important;
    padding-top: 20px;
}

.btn-edit {
    margin-right: 5px;
}

.edit-role-form {
    display: none;
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
}

.edit-role-form.show {
    display: block;
}

.role-display {
    display: inline-block;
}

.table td {
    vertical-align: middle;
}

.action-buttons {
    white-space: nowrap;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 0.875rem;
}

.form-select-sm {
    padding: 4px 8px;
    font-size: 0.875rem;
}
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Manage All Users</h1>
            <p class="text-muted">View, edit, and delete user accounts</p>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php 
    echo $_SESSION['success_message']; 
    unset($_SESSION['success_message']);
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php 
    echo $_SESSION['error_message']; 
    unset($_SESSION['error_message']);
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" id="searchInput" placeholder="Search by username or role...">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="roleFilter">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>All Users List</h5>
            </div>
            <div class="card-body">
                <?php if (count($users) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr class="user-row">
                                <td class="user-id">
                                    <?php echo $user['id']; ?>
                                </td>
                                <td class="user-username">
                                    <strong><?php echo $user['username']; ?></strong>
                                </td>
                                <td class="user-role">
                                    <div class="role-display" id="role-display-<?php echo $user['id']; ?>">
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Student</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Edit Role Form (Hidden by default) -->
                                    <div class="edit-role-form" id="edit-form-<?php echo $user['id']; ?>">
                                        <form method="POST" action="" class="d-flex align-items-center gap-2">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <select name="new_role" class="form-select form-select-sm" style="width: 100px;;">
                                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                                            </select>
                                            <button type="submit" name="edit_role" class="btn btn-success btn-sm">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelEditRole(<?php echo $user['id']; ?>)">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    <?php echo date('Y-m-d H:i:s', strtotime($user['created_at'])); ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <!-- Edit Role Button -->
                                            <button type="button" class="btn btn-sm btn-warning btn-edit" 
                                                    onclick="editRole(<?php echo $user['id']; ?>)" 
                                                    title="Edit Role">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <a href="javascript:void(0);" 
                                               onclick="confirmDelete(<?php echo $user['id']; ?>, '<?php echo $user['username']; ?>', '<?php echo $user['role']; ?>')" 
                                               class="btn btn-sm btn-danger" 
                                               title="Delete User">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Current User</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    No users found in the database.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function editRole(userId) {
    // Hide role display and show edit form
    document.getElementById('role-display-' + userId).style.display = 'none';
    document.getElementById('edit-form-' + userId).classList.add('show');
}

function cancelEditRole(userId) {
    // Show role display and hide edit form
    document.getElementById('role-display-' + userId).style.display = 'inline-block';
    document.getElementById('edit-form-' + userId).classList.remove('show');
}

function confirmDelete(id, username, role) {
    const roleText = role === 'admin' ? 'admin' : 'student';
    if (confirm(`Are you sure you want to delete the ${roleText} account for "${username}"?\n\nThis action cannot be undone and will also delete associated student data if any.`)) {
        window.location.href = `all_users.php?delete=${id}`;
    }
}

// HANYA SATU event listener DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value.toLowerCase();
        const userRows = document.querySelectorAll('.user-row');
        
        userRows.forEach(row => {
            const username = row.querySelector('.user-username').textContent.toLowerCase();
            
            // PERBAIKAN: Ambil teks role dari elemen yang tepat
            const roleElement = row.querySelector('.user-role');
            let roleText = '';
            
            // Cek apakah ada role-display yang visible
            const roleDisplay = roleElement.querySelector('.role-display');
            if (roleDisplay && window.getComputedStyle(roleDisplay).display !== 'none') {
                roleText = roleDisplay.textContent.toLowerCase();
            } else {
                // Fallback: ambil dari semua badge dalam role element
                const badges = roleElement.querySelectorAll('.badge');
                badges.forEach(badge => {
                    if (window.getComputedStyle(badge.closest('.role-display') || badge.parentElement).display !== 'none') {
                        roleText += badge.textContent.toLowerCase() + ' ';
                    }
                });
            }
            
            const matchesSearch = username.includes(searchTerm) || 
                                roleText.includes(searchTerm);
            
            const matchesRole = selectedRole === '' || roleText.includes(selectedRole);
            
            if (matchesSearch && matchesRole) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keyup', filterUsers);
    }
    
    if (roleFilter) {
        roleFilter.addEventListener('change', filterUsers);
    }
});
</script>

<?php include_once '../includes/footer.php'; ?>