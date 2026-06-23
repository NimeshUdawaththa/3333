<?php
/**
 * Authentication Middleware
 * Handles authentication checks and role-based access control
 */

require_once(__DIR__ . '/../../config/db.php');
require_once(__DIR__ . '/../../config/session_config.php');

class AuthMiddleware {
    
    /**
     * Check if user is logged in
     * @return bool
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Require authentication
     * Redirects to login if not authenticated
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header("Location: " . FRONTEND_URL . "auth/login.php?msg=Please login to continue");
            exit;
        }
    }
    
    /**
     * Require specific role(s)
     * @param array|string $roles
     */
    public static function requireRole($roles) {
        self::requireLogin();
        
        if (is_string($roles)) {
            $roles = [$roles];
        }
        
        if (!in_array($_SESSION['user_role'], $roles)) {
            header("Location: " . FRONTEND_URL . "error/403.php");
            exit;
        }
    }
    
    /**
     * Get current user ID
     * @return int|null
     */
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     * @return string|null
     */
    public static function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Get current user info
     * @param mysqli $conn
     * @return array|null
     */
    public static function getCurrentUser($conn) {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        $user_id = self::getUserId();
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    /**
     * Verify role permission
     * @param string $role
     * @return bool
     */
    public static function hasRole($role) {
        return self::isLoggedIn() && $_SESSION['user_role'] === $role;
    }
    
    /**
     * Verify one of multiple roles
     * @param array $roles
     * @return bool
     */
    public static function hasAnyRole($roles) {
        return self::isLoggedIn() && in_array($_SESSION['user_role'], $roles);
    }
}

?>
