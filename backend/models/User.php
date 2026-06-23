<?php
/**
 * User Model
 * Handles user-related database operations
 */

class User {
    private $db;
    private $user_id;
    private $username;
    private $email;
    private $full_name;
    private $user_role;
    private $is_active;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Create new user
     */
    public function create($username, $email, $password, $full_name, $user_role, $phone = null) {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (username, email, password_hash, full_name, user_role, phone)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return ['success' => false, 'error' => $this->db->error];
        }

        $stmt->bind_param("ssssss", $username, $email, $password_hash, $full_name, $user_role, $phone);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'user_id' => $this->db->insert_id,
                'message' => 'User created successfully'
            ];
        } else {
            return ['success' => false, 'error' => $stmt->error];
        }
    }

    /**
     * Get user by ID
     */
    public function getById($user_id) {
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get user by username
     */
    public function getByUsername($username) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Verify password
     */
    public function verifyPassword($username, $password) {
        $user = $this->getByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            return [
                'success' => true,
                'user' => $user
            ];
        }

        return ['success' => false, 'error' => 'Invalid credentials'];
    }

    /**
     * Update user
     */
    public function update($user_id, $data) {
        $allowed_fields = ['email', 'full_name', 'phone', 'profile_picture'];

        $update_parts = [];
        $params = [];
        $types = '';

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed_fields)) {
                $update_parts[] = "$key = ?";
                $params[] = $value;
                $types .= 's';
            }
        }

        if (empty($update_parts)) {
            return ['success' => false, 'error' => 'No valid fields to update'];
        }

        $params[] = $user_id;
        $types .= 'i';

        $query = "UPDATE users SET " . implode(", ", $update_parts) . " WHERE user_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User updated successfully'];
        } else {
            return ['success' => false, 'error' => $stmt->error];
        }
    }

    /**
     * Get all users
     */
    public function getAll($limit = 50, $offset = 0) {
        $query = "SELECT user_id, username, email, full_name, user_role, is_active, created_at
                  FROM users
                  ORDER BY created_at DESC
                  LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get users by role
     */
    public function getByRole($role) {
        $query = "SELECT * FROM users WHERE user_role = ? AND is_active = TRUE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $role);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Deactivate user
     */
    public function deactivate($user_id) {
        $query = "UPDATE users SET is_active = FALSE WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User deactivated'];
        }
        return ['success' => false, 'error' => $stmt->error];
    }

    /**
     * Delete user (soft delete recommended)
     */
    public function delete($user_id) {
        // Soft delete by setting is_active to FALSE
        return $this->deactivate($user_id);
    }
}

?>
