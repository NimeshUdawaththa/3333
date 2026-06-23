<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard - Smart Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
    AuthMiddleware::requireRole('lecturer');
    ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar">
                <div class="text-center mb-30">
                    <h5 style="color: white; margin-top: 10px;">Lecturer Panel</h5>
                </div>
                
                <div class="nav-label">Main</div>
                <a href="lecturer_dashboard.php" class="active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                
                <div class="nav-label">Teaching</div>
                <a href="<?php echo FRONTEND_URL; ?>lecturer/my_courses.php">
                    <i class="bi bi-book"></i> My Courses
                </a>
                <a href="<?php echo FRONTEND_URL; ?>lecturer/assignments.php">
                    <i class="bi bi-file-earmark-text"></i> Assignments
                </a>
                <a href="<?php echo FRONTEND_URL; ?>lecturer/attendance.php">
                    <i class="bi bi-check-circle"></i> Attendance
                </a>
                <a href="<?php echo FRONTEND_URL; ?>lecturer/marks.php">
                    <i class="bi bi-graph-up"></i> Marks
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
                    <h2 class="mb-4">Lecturer Dashboard</h2>
                    
                    <!-- Statistics -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6>My Courses</h6>
                                <h3>
                                    <?php
                                    $user_id = AuthMiddleware::getUserId();
                                    $query = "SELECT COUNT(*) as count FROM courses WHERE lecturer_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
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
                                    $query = "SELECT COUNT(DISTINCT ce.student_id) as count 
                                              FROM course_enrollment ce
                                              JOIN courses c ON ce.course_id = c.course_id
                                              WHERE c.lecturer_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6>Assignments</h6>
                                <h3>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM assignments WHERE lecturer_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6>Pending Submissions</h6>
                                <h3>
                                    <?php
                                    $query = "SELECT COUNT(*) as count FROM assignment_submissions WHERE status = 'pending'";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                    
                    <!-- My Courses -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="dashboard-card">
                                <h5 class="mb-4">My Courses</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th>Students</th>
                                                <th>Semester</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT c.course_id, c.course_code, c.course_name, c.semester,
                                                     COUNT(DISTINCT ce.student_id) as student_count
                                                     FROM courses c
                                                     LEFT JOIN course_enrollment ce ON c.course_id = ce.course_id
                                                     WHERE c.lecturer_id = ?
                                                     GROUP BY c.course_id
                                                     ORDER BY c.course_code";
                                            $stmt = $conn->prepare($query);
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td><strong>' . htmlspecialchars($row['course_code']) . '</strong></td>';
                                                    echo '<td>' . htmlspecialchars($row['course_name']) . '</td>';
                                                    echo '<td>' . $row['student_count'] . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['semester']) . '</td>';
                                                    echo '<td><a href="' . FRONTEND_URL . 'lecturer/course_details.php?id=' . $row['course_id'] . '" class="btn btn-sm btn-primary">View</a></td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="5" class="text-center text-muted">No courses assigned</td></tr>';
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
