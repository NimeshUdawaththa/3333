<?php
/**
 * Navigation Header Component
 */

require_once(__DIR__ . '/../../config/db.php');
require_once(__DIR__ . '/../../config/session_config.php');
require_once(__DIR__ . '/../../backend/middleware/auth_middleware.php');

// Ensure user is logged in
AuthMiddleware::requireLogin();

$user_id = AuthMiddleware::getUserId();
$user_role = AuthMiddleware::getUserRole();
$current_user = AuthMiddleware::getCurrentUser($conn);

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo FRONTEND_URL; ?>dashboard/">
            <strong>📚 Smart SMS</strong>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">
                        Welcome, <strong><?php echo htmlspecialchars($current_user['full_name'] ?? 'User'); ?></strong>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link badge bg-info">
                        <?php echo ucfirst(str_replace('_', ' ', $user_role)); ?>
                    </span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> Profile
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="<?php echo FRONTEND_URL; ?>profile/profile.php">My Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo FRONTEND_URL; ?>profile/change_password.php">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BACKEND_URL; ?>api/auth/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
