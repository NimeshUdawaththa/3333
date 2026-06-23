<?php
/**
 * Logout Process
 */

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../auth/auth.php');

$auth = new Auth($conn);
$auth->logout();

header("Location: " . FRONTEND_URL . "auth/login.php?msg=You have been logged out successfully");
exit;

?>
