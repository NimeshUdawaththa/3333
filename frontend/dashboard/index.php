<?php
/**
 * Dashboard Redirect
 * Redirects to appropriate dashboard based on user role
 */

require_once(__DIR__ . '/../../config/db.php');
require_once(__DIR__ . '/../../backend/middleware/auth_middleware.php');

AuthMiddleware::requireLogin();

$role = AuthMiddleware::getUserRole();

switch ($role) {
    case 'admin':
        header("Location: admin_dashboard.php");
        break;
    case 'academic_staff':
        header("Location: academic_dashboard.php");
        break;
    case 'lecturer':
        header("Location: lecturer_dashboard.php");
        break;
    case 'student':
        header("Location: student_dashboard.php");
        break;
    default:
        header("Location: " . FRONTEND_URL . "auth/login.php?msg=Unknown role");
}
exit;

?>
