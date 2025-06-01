<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Check if student ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$studentId = $_GET['id'];

// Include database connection and functions
require_once '../config/database.php';
require_once '../config/init.php';

// Get student data
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM students WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $studentId);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    // Student not found
    $_SESSION['error_message'] = "Student not found.";
    header("Location: students.php");
    exit();
}

$student = $stmt->fetch(PDO::FETCH_ASSOC);
$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = sanitizeInput($_POST['name']);
    $nim = sanitizeInput($_POST['nim']);
    $birth_date = sanitizeInput($_POST['birth_date']);
    $address = sanitizeInput($_POST['address']);
    $phone = sanitizeInput($_POST['phone']);
    $hobbies = sanitizeInput($_POST['hobbies']);
    
    // Validate inputs server-side
    if (empty($name) || empty($nim) || empty($birth_date) || empty($address) || empty($phone)) {
        $error = "All fields except hobbies are required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Name should contain only letters.";
    } elseif (!preg_match("/^[0-9]+$/", $nim)) {
        $error = "NIM should contain only numbers.";
    } elseif (!preg_match("/^[0-9]+$/", $phone)) {
        $error = "Phone should contain only numbers.";
    } else {
        // Check if NIM already exists and doesn't belong to current student
        $query = "SELECT id FROM students WHERE nim = :nim AND id != :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nim', $nim);
        $stmt->bindParam(':id', $studentId);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $error = "NIM already exists. Please use a different NIM.";
        } else {
            // Update student profile
            $query = "UPDATE students 
                      SET name = :name, nim = :nim, birth_date = :birth_date, 
                          address = :address, phone = :phone, hobbies = :hobbies, 
                          updated_at = NOW() 
                      WHERE id = :id";
                      
            $stmt = $db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':nim', $nim);
            $stmt->bindParam(':birth_date', $birth_date);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':hobbies', $hobbies);
            $stmt->bindParam(':id', $studentId);
            
            if ($stmt->execute()) {
                // Log the activity
                $changes = [];
                if ($name != $student['name']) $changes[] = "name from '{$student['name']}' to '$name'";
                if ($nim != $student['nim']) $changes[] = "NIM from '{$student['nim']}' to '$nim'";
                if ($birth_date != $student['birth_date']) $changes[] = "birth date";
                if ($address != $student['address']) $changes[] = "address";
                if ($phone != $student['phone']) $changes[] = "phone";
                if ($hobbies != $student['hobbies']) $changes[] = "hobbies";
                
                $changeDetails = empty($changes) ? "No changes made" : "Changed " . implode(', ', $changes);
                logActivity($_SESSION['user_id'], 'Edit Student', $changeDetails);
                
                $success = "Student information updated successfully!";
                
                // Refresh the student data
                $query = "SELECT * FROM students WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $studentId);
                $stmt->execute();
                $student = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = "Failed to update student information. Please try again.";
            }
        }
    }
}

// Include header
include_once '../includes/header.php';
?>
<body class="body-page">
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Edit Student</h1>
            <p class="text-muted">Update student information</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Student Information</h5>
            </div>
            <div class="card-body">
                <form id="studentForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?id=' . $studentId); ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $student['name']; ?>" required>
                        <small class="form-text text-muted">Enter full name (letters only)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM (Student ID)</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $student['nim']; ?>" required>
                        <small class="form-text text-muted">Enter student ID number (numbers only)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?php echo $student['birth_date']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $student['address']; ?></textarea>
                        <small class="form-text text-muted">Enter complete address</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $student['phone']; ?>" required>
                        <small class="form-text text-muted">Enter phone number (numbers only)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hobbies" class="form-label">Hobbies</label>
                        <textarea class="form-control" id="hobbies" name="hobbies" rows="2"><?php echo $student['hobbies']; ?></textarea>
                        <small class="form-text text-muted">Optional: List hobbies</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Update Student
                        </button>
                        <a href="students.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Students List
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>