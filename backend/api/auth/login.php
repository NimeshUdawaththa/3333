<?php
/**
 * Authentication API Endpoint
 * Handles user login/logout
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/User.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $action = $_GET['action'] ?? null;

    if ($action === 'login') {
        handleLogin($db);
    } elseif ($action === 'logout') {
        handleLogout($db);
    } elseif ($action === 'refresh-token') {
        handleRefreshToken($db);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

/**
 * Handle user login
 */
function handleLogin($db) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['username']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Username and password required']);
        return;
    }

    $user = new User($db);
    $result = $user->verifyPassword($data['username'], $data['password']);

    if ($result['success']) {
        $user_data = $result['user'];

        // Check if user is active
        if (!$user_data['is_active']) {
            http_response_code(403);
            echo json_encode(['error' => 'Account is inactive']);
            return;
        }

        // Create session
        session_start();
        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['user_role'] = $user_data['user_role'];
        $_SESSION['full_name'] = $user_data['full_name'];
        $_SESSION['login_time'] = time();

        // Log to sessions table
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $query = "INSERT INTO sessions (session_id, user_id, ip_address, user_agent, is_active)
                  VALUES (?, ?, ?, ?, TRUE)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("siss", session_id(), $user_data['user_id'], $ip, $user_agent);
        $stmt->execute();

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'user_id' => $user_data['user_id'],
                'username' => $user_data['username'],
                'full_name' => $user_data['full_name'],
                'user_role' => $user_data['user_role'],
                'email' => $user_data['email']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
}

/**
 * Handle user logout
 */
function handleLogout($db) {
    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $session_id = session_id();

        // Update session in database
        $query = "UPDATE sessions SET is_active = FALSE, logout_time = NOW()
                  WHERE session_id = ? AND user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $session_id, $user_id);
        $stmt->execute();

        // Destroy session
        session_destroy();

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Logout successful']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No active session']);
    }
}

/**
 * Handle token refresh (for JWT)
 */
function handleRefreshToken($db) {
    // Implementation for JWT token refresh
    http_response_code(501);
    echo json_encode(['error' => 'Not implemented']);
}

?>
