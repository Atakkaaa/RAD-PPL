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

if ($stmt->rowCount() == 0) {
    // Profile doesn't exist, redirect to create profile
    header("Location: create_profile.php");
    exit();
}

$student = $stmt->fetch(PDO::FETCH_ASSOC);
$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = sanitizeInput($_POST['name']);
    $birth_date = sanitizeInput($_POST['birth_date']);
    $address = sanitizeInput($_POST['address']);
    $phone = sanitizeInput($_POST['phone']);
    $hobbies = sanitizeInput($_POST['hobbies']);
    
    // Validate inputs server-side
    if (empty($name) || empty($birth_date) || empty($address) || empty($phone)) {
        $error = "All fields except hobbies are required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Name should contain only letters.";
    } elseif (!preg_match("/^[0-9]+$/", $phone)) {
        $error = "Phone should contain only numbers.";
    } else {
        // Update student profile
        $query = "UPDATE students 
                  SET name = :name, birth_date = :birth_date, address = :address, 
                      phone = :phone, hobbies = :hobbies, updated_at = NOW() 
                  WHERE user_id = :user_id";
                  
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birth_date', $birth_date);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':hobbies', $hobbies);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            // Log the activity
            $changes = [];
            if ($name != $student['name']) $changes[] = "name from '{$student['name']}' to '$name'";
            if ($birth_date != $student['birth_date']) $changes[] = "birth date";
            if ($address != $student['address']) $changes[] = "address";
            if ($phone != $student['phone']) $changes[] = "phone";
            if ($hobbies != $student['hobbies']) $changes[] = "hobbies";
            
            $changeDetails = empty($changes) ? "No changes made" : "Changed " . implode(', ', $changes);
            logActivity($_SESSION['user_id'], 'Edit Profile', $changeDetails);
            
            $success = "Profile updated successfully!";
            // Refresh the student data
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = "Failed to update profile. Please try again.";
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
            <h1>EDIT PROFILE</h1>
            <p class="text-muted">Update your profile information  &#128519; </p>
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
                <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Update Student Information</h5>
            </div>
            <div class="card-body">
                <form id="studentForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $student['name']; ?>" required>
                        <small class="form-text text-muted">Enter your full name (letters only)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM (Student ID)</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $student['nim']; ?>" readonly>
                        <small class="form-text text-muted">NIM cannot be changed</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?php echo $student['birth_date']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $student['address']; ?></textarea>
                        <small class="form-text text-muted">Enter your complete address</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $student['phone']; ?>" required>
                        <small class="form-text text-muted">Enter your phone number (numbers only)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hobbies" class="form-label">Hobbies</label>
                        <textarea class="form-control" id="hobbies" name="hobbies" rows="2"><?php echo $student['hobbies']; ?></textarea>
                        <small class="form-text text-muted">Optional: List your hobbies</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Update Profile
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