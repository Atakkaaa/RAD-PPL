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

// Delete student if requested
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $studentId = $_GET['delete'];
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Get student NIM for logging
    $query = "SELECT nim FROM students WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $studentId);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        // Delete the student
        $query = "DELETE FROM students WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $studentId);
        
        if ($stmt->execute()) {
            // Log the activity
            logActivity($_SESSION['user_id'], 'Delete Student', "Deleted student with NIM: {$student['nim']}");
            
            // Set success message
            $_SESSION['success_message'] = "Student deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete student.";
        }
    } else {
        $_SESSION['error_message'] = "Student not found.";
    }
    
    // Redirect to remove the delete parameter
    header("Location: students.php");
    exit();
}

// Get all students
$database = new Database();
$db = $database->getConnection();

$query = "SELECT s.*, u.username 
          FROM students s 
          JOIN users u ON s.user_id = u.id 
          ORDER BY s.name ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include_once '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>MANAGE STUDENTS</h1>
            <p class="text-muted">View, edit, and delete student records</p>
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

<body class="body-page">
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" id="searchInput" placeholder="Search by name, NIM, or address...">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Student List</h5>
            </div>
            <div class="card-body">
                <?php if (count($students) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>NIM</th>
                                <th>Username</th>
                                <th>Birth Date</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                            <tr class="student-row">
                                <td class="student-name"><?php echo $student['name']; ?></td>
                                <td class="student-nim"><?php echo $student['nim']; ?></td>
                                <td><?php echo $student['username']; ?></td>
                                <td><?php echo date('M j, Y', strtotime($student['birth_date'])); ?></td>
                                <td class="student-address">
                                    <?php echo $student['address']; ?>
                                    <button class="btn btn-sm btn-secondary ms-1 direction-btn" 
                                            data-address="<?php echo $student['address']; ?>">
                                        <img src="../assets/img/maps.png" style="height: 25px;">
                                    </button>
                                </td>
                                <td><?php echo $student['phone']; ?></td>
                                <td>
                                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $student['id']; ?>, '<?php echo $student['name']; ?>')" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    No students found in the database.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete the record for ${name}?`)) {
        window.location.href = `students.php?delete=${id}`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const studentRows = document.querySelectorAll('.student-row');
            
            studentRows.forEach(row => {
                const name = row.querySelector('.student-name').textContent.toLowerCase();
                const nim = row.querySelector('.student-nim').textContent.toLowerCase();
                const address = row.querySelector('.student-address').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || nim.includes(searchTerm) || address.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>

<?php include_once '../includes/footer.php'; ?>