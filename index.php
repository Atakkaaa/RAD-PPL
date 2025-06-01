<?php
session_start();
// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Redirect based on role
if ($_SESSION['role'] == 'admin') {
    header("Location: admin/dashboard.php");
} else {
    header("Location: student/dashboard.php");
}
exit();
?>