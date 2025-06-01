<?php
session_start();

// Include database functions
require_once '../config/init.php';

// Log the logout activity if user is logged in
if (isset($_SESSION['user_id'])) {
    logActivity($_SESSION['user_id'], 'Logout', 'User logged out');
    
    // Destroy the session
    session_unset();
    session_destroy();
}

// Redirect to login page
header("Location: login.php");
exit();
?>