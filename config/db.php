<?php
/**
 * Database Connection Configuration
 * Handles MySQL database connection for the Smart Student Management System
 */

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smart_student_management');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Define base URL for redirects
define('BASE_URL', 'http://localhost/Smart-Student-Management-System/');
define('FRONTEND_URL', BASE_URL . 'frontend/');
define('BACKEND_URL', BASE_URL . 'backend/');

?>
