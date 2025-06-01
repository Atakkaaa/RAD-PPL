
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

// Get statistics
$database = new Database();
$db = $database->getConnection();

// Total students
$query = "SELECT COUNT(*) as total_students FROM students";
$stmt = $db->prepare($query);
$stmt->execute();
$totalStudents = $stmt->fetch(PDO::FETCH_ASSOC)['total_students'];

// Total users
$query = "SELECT COUNT(*) as total_users FROM users";
$stmt = $db->prepare($query);
$stmt->execute();
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Recent activities
$query = "SELECT a.*, u.username 
          FROM activity_logs a 
          JOIN users u ON a.user_id = u.id 
          ORDER BY a.created_at DESC 
          LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$recentActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recently added students
$query = "SELECT s.*, u.username 
          FROM students s 
          JOIN users u ON s.user_id = u.id 
          ORDER BY s.created_at DESC 
          LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$recentStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include_once '../includes/header.php';
?>
<body class="body-page">
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Admin Dashboard</h1>
            <p class="text-muted fw-bold">Welcome, Admin <?php echo $_SESSION['username']; ?>!</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="dashboard-card bg-primary text-white">
            <div class="d-flex justify-content-between">
                <div>
                    <h2><?php echo $totalStudents; ?></h2>
                    <p class="mb-0">Students</p>
                </div>
                <div>
                    <i class="bi bi-people" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="dashboard-card bg-secondary text-white">
            <div class="d-flex justify-content-between">
                <div>
                    <h2><?php echo $totalUsers; ?></h2>
                    <p class="mb-0">Total Users</p>
                </div>
                <div>
                    <i class="bi bi-person-badge" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Activities</h5>
            </div>
            <div class="card-body">
                <?php if (count($recentActivities) > 0): ?>
                    <?php foreach ($recentActivities as $activity): ?>
                        <div class="history-item mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?php echo $activity['username']; ?></strong>
                                    <span class="text-muted ms-2"><?php echo $activity['action']; ?></span>
                                </div>
                                <small class="text-muted">
                                    <?php echo date('M j, H:i', strtotime($activity['created_at'])); ?>
                                </small>
                            </div>
                            <?php if (!empty($activity['details'])): ?>
                                <small class="text-muted"><?php echo $activity['details']; ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center mt-3">
                        <a href="history.php" class="btn btn-sm btn-primary">View All Activities</a>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No recent activities found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Recently Added Students</h5>
            </div>
            <div class="card-body">
                <?php if (count($recentStudents) > 0): ?>
                    <div class="list-group">
                        <?php foreach ($recentStudents as $student): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo $student['name']; ?> (<?php echo $student['nim']; ?>)</h6>
                                    <small><?php echo date('M j', strtotime($student['created_at'])); ?></small>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i><?php echo $student['address']; ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="students.php" class="btn btn-sm btn-primary">View All Students</a>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No students found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="students.php" class="btn btn-primarys w-100">
                            <i class="bi bi-people me-2"></i>Manage Students
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="history.php" class="btn btn-primarys w-100">
                            <i class="bi bi-clock-history me-2"></i>View History
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="all_users.php" class="btn btn-primarys w-100">
                            <i class="bi bi-search me-2"></i>Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>