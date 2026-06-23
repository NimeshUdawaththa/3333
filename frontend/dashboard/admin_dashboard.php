<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #ffc107;
            padding-left: 25px;
        }
        
        .sidebar .nav-label {
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 20px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            margin-top: 20px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stat-card h6 {
            font-size: 12px;
            text-transform: uppercase;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        
        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
        }
        
        .content {
            padding: 30px 20px;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0 0 20px 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar">
                <div class="text-center mb-30">
                    <h5 style="color: white; margin-top: 10px;">Admin Panel</h5>
                </div>
                
                <div class="nav-label">Main</div>
                <a href="admin_dashboard.php" class="active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                
                <div class="nav-label">Management</div>
                <a href="<?php echo FRONTEND_URL; ?>admin/users.php">
                    <i class="bi bi-people"></i> Users
                </a>
                <a href="<?php echo FRONTEND_URL; ?>admin/departments.php">
                    <i class="bi bi-building"></i> Departments
                </a>
                <a href="<?php echo FRONTEND_URL; ?>admin/courses.php">
                    <i class="bi bi-book"></i> Courses
                </a>
                <a href="<?php echo FRONTEND_URL; ?>admin/audit_logs.php">
                    <i class="bi bi-clock-history"></i> Audit Logs
                </a>
                
                <div class="nav-label">Account</div>
                <a href="<?php echo FRONTEND_URL; ?>profile/profile.php">
                    <i class="bi bi-person"></i> Profile
                </a>
                <a href="<?php echo BACKEND_URL; ?>api/auth/logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-10">
                <?php require_once(__DIR__ . '/../components/navbar.php'); ?>
                
                <div class="content">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                    
                    <h2 class="mb-4">Admin Dashboard</h2>
                    
                    <!-- Statistics -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6>Total Users</h6>
                                <h3>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM users";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6>Total Students</h6>
                                <h3>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM students";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6>Total Courses</h6>
                                <h3>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM courses";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6>Total Departments</h6>
                                <h3>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM departments";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activities -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Recent Activities</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Action</th>
                                                <th>Details</th>
                                                <th>Date & Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT action, details, created_at FROM audit_logs ORDER BY created_at DESC LIMIT 10";
                                            $result = $conn->query($query);
                                            
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td><strong>' . htmlspecialchars($row['action']) . '</strong></td>';
                                                    echo '<td>' . htmlspecialchars($row['details']) . '</td>';
                                                    echo '<td>' . date('M d, Y H:i', strtotime($row['created_at'])) . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3" class="text-center text-muted">No activities yet</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
