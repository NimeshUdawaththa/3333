<?php
require_once('config/db.php');

echo "<h2>Testing Login Credentials</h2>";

// Test admin credentials
$username = 'admin';
$password = 'admin123';

$query = "SELECT user_id, username, email, password_hash, user_role, full_name, is_active FROM users WHERE (username = ? OR email = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "<p><strong>User Found:</strong></p>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";
    
    echo "<p><strong>Testing password_verify():</strong></p>";
    $match = password_verify($password, $user['password_hash']);
    echo "Password Match: " . ($match ? "YES ✅" : "NO ❌") . "<br>";
    
    if (!$match) {
        echo "<p>Let me test what the correct hash should be:</p>";
        $test_hash = password_hash($password, PASSWORD_BCRYPT);
        echo "New hash for 'admin123': " . $test_hash . "<br>";
        echo "<p>You can update the user with this hash manually.</p>";
    }
} else {
    echo "<p><strong>❌ User 'admin' not found in database!</strong></p>";
    echo "<p>Please import demo_data.sql first.</p>";
}

$stmt->close();
?>
