<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Smart Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 40px;
            margin-top: 50px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-container h2 {
            margin-bottom: 30px;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: 5px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
        }
        
        .btn-submit:hover {
            color: white;
        }
        
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php
    require_once(__DIR__ . '/../../config/db.php');
    require_once(__DIR__ . '/../../backend/middleware/auth_middleware.php');
    require_once(__DIR__ . '/../../backend/auth/auth.php');
    
    AuthMiddleware::requireLogin();
    
    $message = '';
    $message_type = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validate
        if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
            $message = 'All fields are required';
            $message_type = 'danger';
        } elseif ($new_password !== $confirm_password) {
            $message = 'New passwords do not match';
            $message_type = 'danger';
        } elseif (strlen($new_password) < 6) {
            $message = 'Password must be at least 6 characters';
            $message_type = 'danger';
        } else {
            $auth = new Auth($conn);
            $result = $auth->changePassword(AuthMiddleware::getUserId(), $old_password, $new_password);
            
            if ($result['success']) {
                $message = $result['message'];
                $message_type = 'success';
                $_POST = [];
            } else {
                $message = $result['message'];
                $message_type = 'danger';
            }
        }
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
        <div class="form-container">
            <h2>Change Password</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label" for="old_password">Current Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="old_password" 
                        name="old_password" 
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="new_password">New Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="new_password" 
                        name="new_password" 
                        required
                    >
                    <small class="text-muted">Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="confirm_password" 
                        name="confirm_password" 
                        required
                    >
                </div>
                
                <button type="submit" class="btn-submit">Change Password</button>
            </form>
            
            <div class="text-center mt-3">
                <a href="profile.php" class="text-decoration-none">Back to Profile</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
