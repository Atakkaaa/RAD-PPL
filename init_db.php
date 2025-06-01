<?php
// Initialize database script

// Include database connection
require_once 'config/database.php';

// Connect to MySQL without selecting a database
$host = "localhost";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MySQL successfully!<br>";
    
    // Get SQL script content
    $sql = file_get_contents('config/setup.sql');
    
    // Execute the SQL script
    $pdo->exec($sql);
    
    echo "Database and tables created successfully!<br>";
    echo "Default admin user created:<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<br><a href='index.php'>Go to Application</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>