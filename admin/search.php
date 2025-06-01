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

<body class="body-page">
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>ADVANCED SEARCH</h1>
            <p class="text-muted">Search for students with advanced filtering options</p>
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-filter me-2"></i>Search Results</h5>
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
                                    <button class="btn btn-sm btn-outline-secondary ms-1 direction-btn" 
                                            data-address="<?php echo $student['address']; ?>">
                                        <i class="bi bi-map"></i>
                                    </button>
                                </td>
                                <td><?php echo $student['phone']; ?></td>
                                <td>
                                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
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