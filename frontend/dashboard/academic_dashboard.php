<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Staff Dashboard - Smart Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
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
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
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
    </style>
</head>
<body>
    <?php
    require_once(__DIR__ . '/../../config/db.php');
    require_once(__DIR__ . '/../../backend/middleware/auth_middleware.php');
    AuthMiddleware::requireRole('academic_staff');
    ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar">
                <div class="text-center mb-30">
                    <h5 style="color: white; margin-top: 10px;">Academic Staff</h5>
                </div>
                
                <div class="nav-label">Main</div>
                <a href="academic_dashboard.php" class="active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                
                <div class="nav-label">Management</div>
                <a href="<?php echo FRONTEND_URL; ?>academic/students.php">
                    <i class="bi bi-people"></i> Students
                </a>
                <a href="<?php echo FRONTEND_URL; ?>academic/courses.php">
                    <i class="bi bi-book"></i> Courses
                </a>
                <a href="<?php echo FRONTEND_URL; ?>academic/enrollment.php">
                    <i class="bi bi-person-check"></i> Enrollment
                </a>
                <a href="<?php echo FRONTEND_URL; ?>academic/results.php">
                    <i class="bi bi-file-earmark"></i> Results
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
                    <h2 class="mb-4">Academic Staff Dashboard</h2>
                    
                    <!-- Statistics -->
                    <div class="row">
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
                                <h6>Enrollments</h6>
                                <h3>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM course_enrollment";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6>Active Lecturers</h6>
                                <h3>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM users WHERE user_role = 'lecturer' AND is_active = 1";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Recent Enrollments</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Student</th>
                                                <th>Course</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT u.full_name, c.course_code, ce.enrollment_date
                                                     FROM course_enrollment ce
                                                     JOIN students s ON ce.student_id = s.student_id
                                                     JOIN users u ON s.user_id = u.user_id
                                                     JOIN courses c ON ce.course_id = c.course_id
                                                     ORDER BY ce.enrollment_date DESC
                                                     LIMIT 10";
                                            $result = $conn->query($query);
                                            
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                                                    echo '<td><strong>' . htmlspecialchars($row['course_code']) . '</strong></td>';
                                                    echo '<td>' . date('M d, Y', strtotime($row['enrollment_date'])) . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3" class="text-center text-muted">No recent enrollments</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="dashboard-card">
                                <h5 class="mb-4">System Summary</h5>
                                <p class="mb-3">
                                    <strong>Total Users:</strong>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM users WHERE is_active = 1";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </p>
                                <p class="mb-3">
                                    <strong>Departments:</strong>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM departments WHERE is_active = 1";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </p>
                                <p>
                                    <strong>Avg. Students per Course:</strong>
                                    <?php
                                    $query = "SELECT AVG(student_count) as avg_count FROM (
                                              SELECT COUNT(*) as student_count FROM course_enrollment GROUP BY course_id
                                              ) as subquery";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo round($row['avg_count'] ?? 0, 1);
                                    ?>
                                </p>
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
