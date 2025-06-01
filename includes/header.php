<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && $_SESSION['role'] == 'admin';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $isLoggedIn ? '../' : ''; ?>assets/css/style.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
     <!-- Bootstrap Icons Kiri Atas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php if ($isLoggedIn): ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-header">
         <!-- Header Icon -->
              <div class="mb-1 d-flex align-items-center gap-2">
                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center ms-2" style="width: 35px; height: 35px; ">
                    <i class="fas fa-cogs fa-lg ms-0"></i>
                </div>
                <h6 class="custom-title m-0 fw-bold ms-2" style="font-size: 20px; color: #0b5ed7; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Student Management System</h6>
            </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse fw-bold" id="navbarNav">
            <ul class="navbar-nav me-auto ">
                <li class="nav-item ms-3">
                   <a class="nav-link <?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>" 
                        href="<?php echo $isAdmin ? '../admin/dashboard.php' : '../student/dashboard.php'; ?>">
                        <i class="bi bi-house-door me-1"></i>Dashboard
                        </a>
                </li>
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'students.php' ? 'active' : ''; ?>" 
                       href="../admin/students.php">
                        <i class="bi bi-people me-1"></i>All Students
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'history.php' ? 'active' : ''; ?>" 
                       href="../admin/history.php">
                        <i class="bi bi-clock-history me-1"></i>Activity Log
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'all_users.php' ? 'active' : ''; ?>" 
                       href="../admin/all_users.php">
                        <i class="bi bi-people me-1"></i>All Users
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex">
            <span class="navbar-text me-3 d-flex align-items-center">
                <img src="<?php echo $isAdmin ? '../assets/img/admin.png' : '../assets/img/student.png'; ?>" 
                    alt="Login Image"
                    style="<?php echo $isAdmin ? 'height: 35px; width: auto;' : 'max-width: 30px;'; ?>" 
                    class="me-2">
                <strong><?php echo $_SESSION['username']; ?></strong>
            </span>
           <a href="../auth/logout.php" 
                class="btn btn-outline-danger d-flex align-items-center justify-content-center"
                style="padding: 4px 10px; margin-inline-end : 4px; font-size: 14px; line-height: 1;">
                <i class="bi bi-box-arrow-right me-2 ms-0"></i>Logout
            </a>

        </div>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container mt-4">