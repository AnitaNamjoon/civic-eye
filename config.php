<?php
// config.php - Database Connection for CivicEye
// Connects PHP to MySQL (XAMPP default settings)

$host = 'localhost';
$username = 'root'; // Default XAMPP username
$password = '';     // Default XAMPP password is empty
$database = 'civic_eye';

// Create connection using mysqli
$conn = new mysqli($host, $username, $password, $database);

// Check connection and handle errors clearly
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data to prevent XSS
function sanitize_input($data) {
    if (!$data) return '';
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
