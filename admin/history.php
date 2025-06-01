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

// Get history logs with pagination
$database = new Database();
$db = $database->getConnection();

// Pagination variables
$recordsPerPage = 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Search functionality
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    $whereClause = " WHERE u.username LIKE :search OR a.action LIKE :search OR a.details LIKE :search ";
    $params[':search'] = "%$search%";
}

// Count total records for pagination
$countQuery = "SELECT COUNT(*) as total FROM activity_logs a 
              JOIN users u ON a.user_id = u.id
              $whereClause";
$countStmt = $db->prepare($countQuery);

if (!empty($search)) {
    $countStmt->bindParam(':search', $params[':search']);
}

$countStmt->execute();
$totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get activity logs
$query = "SELECT a.*, u.username, u.role 
          FROM activity_logs a 
          JOIN users u ON a.user_id = u.id
          $whereClause
          ORDER BY a.created_at DESC 
          LIMIT :offset, :limit";
$stmt = $db->prepare($query);

if (!empty($search)) {
    $stmt->bindParam(':search', $params[':search']);
}

$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $recordsPerPage, PDO::PARAM_INT);
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include_once '../includes/header.php';
?>

<body class="body-page">
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Activity Logs</h1>
            <p class="text-muted">View all user activities in the system</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" class="form-control" id="search" name="search" placeholder="Search by username, action, or details..." value="<?php echo $search; ?>">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <?php if (!empty($search)): ?>
                <a href="history.php" class="btn btn-secondary">
                    <i class="bi bi-x"></i>
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Activity History</h5>
            </div>
            <div class="card-body">
                <?php if (count($activities) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td><?php echo date('M j, Y g:i a', strtotime($activity['created_at'])); ?></td>
                                <td><?php echo $activity['username']; ?></td>
                                <td>
                                    <span class="badge <?php echo $activity['role'] === 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
                                        <?php echo ucfirst($activity['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo $activity['action']; ?></td>
                                <td><?php echo $activity['details'] ? $activity['details'] : '-'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page - 1); ?><?php echo !empty($search) ? '&search='.$search : ''; ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search='.$search : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page + 1); ?><?php echo !empty($search) ? '&search='.$search : ''; ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
                
                <?php else: ?>
                <div class="alert alert-info">
                    No activity logs found.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>