<?php
/**
 * Login Processing
 */

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../auth/auth.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        header("Location: " . FRONTEND_URL . "auth/login.php?msg=Username and password are required");
        exit;
    }
    
    // Initialize auth
    $auth = new Auth($conn);
    $result = $auth->login($username, $password);
    
    if ($result['success']) {
        // Redirect based on role
        $role = $_SESSION['user_role'];
        
        switch ($role) {
            case 'admin':
                header("Location: " . FRONTEND_URL . "dashboard/admin_dashboard.php");
                break;
            case 'academic_staff':
                header("Location: " . FRONTEND_URL . "dashboard/academic_dashboard.php");
                break;
            case 'lecturer':
                header("Location: " . FRONTEND_URL . "dashboard/lecturer_dashboard.php");
                break;
            case 'student':
                header("Location: " . FRONTEND_URL . "dashboard/student_dashboard.php");
                break;
            default:
                header("Location: " . FRONTEND_URL . "auth/login.php?msg=Unknown role");
        }
        exit;
    } else {
        header("Location: " . FRONTEND_URL . "auth/login.php?msg=" . urlencode($result['message']));
        exit;
    }
} else {
    header("Location: " . FRONTEND_URL . "auth/login.php");
    exit;
}

?>
