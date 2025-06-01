<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Include database connection and functions
require_once '../config/database.php';
require_once '../config/init.php';

// Get all students
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM students ORDER BY name ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include_once '../includes/header.php';
?>
<body class="body-page">
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>SEARCH STUDENT</h1>
            <p class="text-muted">Find and view information about other students</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" id="searchInput" placeholder="Search by name, NIM, or address...">
        </div>
    </div>
</div>

<div class="row" id="studentList">
    <?php if (count($students) > 0): ?>
        <?php foreach ($students as $student): ?>
            <div class="col-md-6 mb-4 student-card">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 student-name"><?php echo $student['name']; ?></h5>
                        <span class="badge bg-primary student-nim"><?php echo $student['nim']; ?></span>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong><i class="bi bi-calendar me-2"></i>Birth Date:</strong> 
                            <?php echo date('F j, Y', strtotime($student['birth_date'])); ?>
                        </div>
                        <div class="mb-2">
                            <strong><i class="bi bi-geo-alt me-2"></i>Address:</strong> 
                            <span class="student-address"><?php echo $student['address']; ?></span>
                            <button class="btn btn-sm btn-primarys ms-2 direction-btn" 
                                    data-address="<?php echo $student['address']; ?>">
                                <img src="../assets/img/maps.png"> Get Directions
                            </button>
                        </div>
                        <div class="mb-2">
                            <strong><i class="bi bi-telephone me-2"></i>Phone:</strong> 
                            <?php echo $student['phone']; ?>
                        </div>
                        <?php if (!empty($student['hobbies'])): ?>
                        <div class="mb-2">
                            <strong><i class="bi bi-heart me-2"></i>Hobbies:</strong> 
                            <?php echo $student['hobbies']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                No students found in the database.
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row mt-3">
    <div class="col-12 text-center">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>