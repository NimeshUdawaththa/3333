<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Smart Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-container {
            background: white;
            border-radius: 10px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
        }
        
        .error-code {
            font-size: 80px;
            font-weight: bold;
            color: #dc3545;
            margin: 0;
        }
        
        .error-message {
            color: #666;
            margin: 20px 0;
            font-size: 18px;
        }
        
        .error-description {
            color: #999;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
        }
        
        .btn-home:hover {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <p class="error-code">403</p>
        <h2 class="error-message">Access Denied</h2>
        <p class="error-description">
            You don't have permission to access this resource. 
            Please contact your administrator if you believe this is a mistake.
        </p>
        <a href="<?php echo FRONTEND_URL; ?>dashboard/" class="btn-home">Go to Dashboard</a>
    </div>
</body>
</html>
