<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Smart Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .profile-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 50px;
            color: white;
        }
        
        .profile-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #667eea;
            width: 30%;
        }
        
        .info-value {
            color: #333;
            width: 70%;
        }
    </style>
</head>
<body>
    <?php
    require_once(__DIR__ . '/../../config/db.php');
    require_once(__DIR__ . '/../../backend/middleware/auth_middleware.php');
    
    AuthMiddleware::requireLogin();
    
    $user = AuthMiddleware::getCurrentUser($conn);
    $user_id = AuthMiddleware::getUserId();
    
    // Get role-specific info
    if ($user['user_role'] === 'student') {
        $query = "SELECT * FROM students WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $role_info = $stmt->get_result()->fetch_assoc();
    } elseif ($user['user_role'] === 'lecturer') {
        $query = "SELECT * FROM lecturers WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $role_info = $stmt->get_result()->fetch_assoc();
    } elseif ($user['user_role'] === 'academic_staff') {
        $query = "SELECT * FROM academic_staff WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $role_info = $stmt->get_result()->fetch_assoc();
    }
    ?>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo FRONTEND_URL; ?>dashboard/">
                <strong>📚 Smart SMS</strong>
            </a>
            <a href="<?php echo BACKEND_URL; ?>api/auth/logout.php" class="ms-auto btn btn-sm btn-outline-light">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="bi bi-person"></i>
                </div>
                <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p class="text-muted"><?php echo ucfirst(str_replace('_', ' ', $user['user_role'])); ?></p>
            </div>
            
            <div class="profile-info">
                <div class="info-row">
                    <span class="info-label">Username:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['username']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <?php echo $user['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'; ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Member Since:</span>
                    <span class="info-value"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                </div>
            </div>
            
            <?php if ($user['user_role'] === 'student' && $role_info): ?>
            <div class="profile-info">
                <h5 class="mb-3">Academic Information</h5>
                <div class="info-row">
                    <span class="info-label">Student ID:</span>
                    <span class="info-value"><?php echo htmlspecialchars($role_info['student_id']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Enrollment Date:</span>
                    <span class="info-value"><?php echo date('M d, Y', strtotime($role_info['enrollment_date'])); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="change_password.php" class="btn btn-primary">
                    <i class="bi bi-key"></i> Change Password
                </a>
                <a href="<?php echo FRONTEND_URL; ?>dashboard/" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
