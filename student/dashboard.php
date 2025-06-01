<?php
session_start();

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

// Include database connection and functions
require_once '../config/database.php';
require_once '../config/init.php';

// Get student data
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM students WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();

$hasProfile = $stmt->rowCount() > 0;
$student = $hasProfile ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

// Include header
include_once '../includes/header.php';
?>
<body class="body-page">
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>STUDENT DASHBOARD</h1>
            <p class="text-muted fw-bold">Welcome, <?php echo $_SESSION['username']; ?> &#129303;</p>
        </div>
    </div>
</div>

<div class="row">
    <?php if ($hasProfile): ?>
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>My Profile</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Name:</div>
                    <div class="col-md-8"><?php echo $student['name']; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">NIM:</div>
                    <div class="col-md-8"><?php echo $student['nim']; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Birth Date:</div>
                    <div class="col-md-8"><?php echo date('F j, Y', strtotime($student['birth_date'])); ?></div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 fw-bold">Address:</div>
                <div class="col-md-8">
                    <?php echo htmlspecialchars($student['address']); ?>
                    <button class="btn btn-sm btn-secondary ms-2 direction-btn" 
                            data-address="<?php echo htmlspecialchars($student['address']); ?>">
                        <img src="../assets/img/maps.png" alt="Maps Icon" style="width: 18px; height: 18px; margin-right: 4px;">
                        Show Directions
                    </button>
                </div>
            </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Phone:</div>
                    <div class="col-md-8"><?php echo $student['phone']; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Hobbies:</div>
                    <div class="col-md-8"><?php echo $student['hobbies'] ? $student['hobbies'] : 'Not specified'; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Last Updated:</div>
                    <div class="col-md-8"><?php echo date('F j, Y, g:i a', strtotime($student['updated_at'])); ?></div>
                </div>
                <div class="text-end mt-3">
                    <a href="edit_profile.php" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-card">
            <h5><i class="bi bi-search me-2"></i>Find Other Students</h5>
            <p>Search for other students in your class.</p>
            <a href="search.php" class="btn btn-secondary w-100">
                <i class="bi bi-search me-1"></i>Search Students
            </a>
        </div>
    </div>
    <?php else: ?>
    <div class="col-md-12">
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Welcome to the Student Management System!</h4>
            <p>You haven't created your profile yet. Please complete your profile to continue.</p>
            <hr>
            <a href="create_profile.php" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i>Create My Profile
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>