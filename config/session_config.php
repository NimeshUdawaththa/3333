<?php
/**
 * Session Configuration
 * Handles session settings and security
 */

// Session Configuration
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Session timeout in seconds (30 minutes)
define('SESSION_TIMEOUT', 1800);

// Set session cookie parameters
session_set_cookie_params([
    'lifetime' => SESSION_TIMEOUT,
    'path' => '/',
    'domain' => '',
    'secure' => false, // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Check if session has expired
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        // Session expired
        session_unset();
        session_destroy();
        header("Location: " . FRONTEND_URL . "auth/login.php?msg=Session expired. Please login again.");
        exit;
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

?>
