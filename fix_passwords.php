<?php
require_once('config/db.php');

echo "<h2>Fixing Demo User Passwords</h2>";

// Define demo users with correct passwords
$users = [
    ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
    ['username' => 'lecturer', 'password' => 'lecturer123', 'role' => 'lecturer'],
    ['username' => 'lecturer2', 'password' => 'lecturer123', 'role' => 'lecturer'],
    ['username' => 'academic', 'password' => 'academic123', 'role' => 'academic_staff'],
    ['username' => 'student', 'password' => 'student123', 'role' => 'student'],
    ['username' => 'student2', 'password' => 'student123', 'role' => 'student'],
];

foreach ($users as $user) {
    // Generate correct bcrypt hash
    $password_hash = password_hash($user['password'], PASSWORD_BCRYPT);
    
    // Update user password
    $query = "UPDATE users SET password_hash = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $password_hash, $user['username']);
    
    if ($stmt->execute()) {
        echo "<p>✅ Updated <strong>" . $user['username'] . "</strong> password</p>";
    } else {
        echo "<p>❌ Failed to update <strong>" . $user['username'] . "</strong></p>";
    }
    $stmt->close();
}

echo "<p><strong>All passwords have been fixed!</strong></p>";
echo "<p>You can now login with:</p>";
echo "<ul>";
echo "<li><strong>Admin:</strong> admin / admin123</li>";
echo "<li><strong>Lecturer:</strong> lecturer / lecturer123</li>";
echo "<li><strong>Academic Staff:</strong> academic / academic123</li>";
echo "<li><strong>Student:</strong> student / student123</li>";
echo "</ul>";
echo "<p><a href='frontend/auth/login.php'>Go to Login</a></p>";
?>
