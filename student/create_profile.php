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

// Check if profile already exists
$database = new Database();
$db = $database->getConnection();

$query = "SELECT id FROM students WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Profile already exists, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}

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
        // Check if NIM already exists
        $query = "SELECT id FROM students WHERE nim = :nim";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nim', $nim);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $error = "NIM already exists. Please use a different NIM.";
        } else {
            // Insert student profile
            $query = "INSERT INTO students (user_id, name, nim, birth_date, address, phone, hobbies) 
                      VALUES (:user_id, :name, :nim, :birth_date, :address, :phone, :hobbies)";
                      
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':nim', $nim);
            $stmt->bindParam(':birth_date', $birth_date);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':hobbies', $hobbies);
            
            if ($stmt->execute()) {
                // Log the activity
                logActivity($_SESSION['user_id'], 'Create Profile', "Created student profile with NIM: $nim");
                
                $success = "Profile created successfully!";
                // Redirect to dashboard after a short delay
                header("refresh:2;url=dashboard.php");
            } else {
                $error = "Failed to create profile. Please try again.";
            }
        }
    }
}

// Include header
include_once '../includes/header.php';
?>
<body class="body-page">
    <div class="col-md-12">
        <div class="page-header">
            <h1>CREATE PROFIL</h1>
            <p class="text-muted">Complete your profile information</p>
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
                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Student Information</h5>
            </div>
            <div class="card-body">
                <form id="studentForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small class="form-text text-muted">Enter your full name (letters only)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM (Student ID)</label>
                        <input type="text" class="form-control" id="nim" name="nim" required>
                        <small class="form-text text-muted">Enter your student ID number (numbers only)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        <small class="form-text text-muted">Enter your complete address</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                        <small class="form-text text-muted">Enter your phone number (numbers only)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hobbies" class="form-label">Hobbies</label>
                        <textarea class="form-control" id="hobbies" name="hobbies" rows="2"></textarea>
                        <small class="form-text text-muted">Optional: List your hobbies</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Save Profile
                        </button>
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>