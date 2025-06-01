<?php
// Utility functions and common includes
function logActivity($userId, $action, $details = '') {
    include_once 'database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO activity_logs (user_id, action, details, created_at) 
              VALUES (:user_id, :action, :details, NOW())";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':action', $action);
    $stmt->bindParam(':details', $details);
    
    return $stmt->execute();
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isLoggedIn() {
    if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        return true;
    }
    return false;
}

function isAdmin() {
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        return true;
    }
    return false;
}

function redirectTo($location) {
    header("Location: $location");
    exit();
}
?>