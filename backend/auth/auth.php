<?php
/**
 * Authentication Class
 * Handles user login, logout, and authentication logic
 */

require_once(__DIR__ . '/../../config/db.php');

class Auth {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Authenticate user with username/email and password
     * @param string $username Username or Email
     * @param string $password User password
     * @return array|bool User data or false
     */
    public function login($username, $password) {
        // Prepare query to find user by username or email
        $query = "SELECT user_id, username, email, password_hash, user_role, full_name, is_active 
                  FROM users 
                  WHERE (username = ? OR email = ?) AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }
        
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            return ['success' => false, 'message' => 'Invalid username/email or password'];
        }
        
        $user = $result->fetch_assoc();
        
        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            $this->logLoginAttempt($username, false);
            return ['success' => false, 'message' => 'Invalid username/email or password'];
        }
        
        // Log successful login
        $this->logLoginAttempt($username, true);
        
        // Create session
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_role'] = $user['user_role'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['last_activity'] = time();
        
        return ['success' => true, 'message' => 'Login successful', 'user' => $user];
    }
    
    /**
     * Register new user
     * @param array $data User data
     * @return array
     */
    public function register($data) {
        // Validate input
        if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['full_name'])) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        // Check if username already exists
        $query = "SELECT user_id FROM users WHERE username = ? OR email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $data['username'], $data['email']);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Username or Email already exists'];
        }
        
        // Hash password
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $role = $data['role'] ?? 'student';
        
        // Insert user
        $query = "INSERT INTO users (username, email, password_hash, full_name, user_role, phone, is_active) 
                  VALUES (?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }
        
        $phone = $data['phone'] ?? null;
        $stmt->bind_param("ssssss", $data['username'], $data['email'], $password_hash, $data['full_name'], $role, $phone);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User registered successfully'];
        } else {
            return ['success' => false, 'message' => 'Registration failed: ' . $stmt->error];
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        return true;
    }
    
    /**
     * Log login attempts
     * @param string $username
     * @param bool $success
     */
    private function logLoginAttempt($username, $success) {
        $query = "INSERT INTO audit_logs (user_id, action_type, new_values, created_at) 
                  VALUES (NULL, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $action_type = $success ? 'Login Successful' : 'Login Failed';
        $details = json_encode(["username" => $username]);
        $stmt->bind_param("ss", $action_type, $details);
        $stmt->execute();
    }
    
    /**
     * Update user password
     * @param int $user_id
     * @param string $old_password
     * @param string $new_password
     * @return array
     */
    public function changePassword($user_id, $old_password, $new_password) {
        // Get current password hash
        $query = "SELECT password_hash FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        $user = $result->fetch_assoc();
        
        // Verify old password
        if (!password_verify($old_password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // Hash new password
        $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
        
        // Update password
        $query = "UPDATE users SET password_hash = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $new_hash, $user_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Password changed successfully'];
        } else {
            return ['success' => false, 'message' => 'Password change failed'];
        }
    }
}

?>
