-- ============================================================================
-- Web-Based Smart Student Management and Attendance System
-- MySQL Database Schema
-- ============================================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS smart_student_management;
USE smart_student_management;

-- ============================================================================
-- 1. USERS TABLE (Base user entity for all roles)
-- ============================================================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    user_role ENUM('admin', 'academic_staff', 'lecturer', 'student') NOT NULL,
    profile_picture VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_role (user_role),
    INDEX idx_is_active (is_active)
);

-- ============================================================================
-- 2. DEPARTMENTS TABLE
-- ============================================================================
CREATE TABLE departments (
    dept_id INT PRIMARY KEY AUTO_INCREMENT,
    dept_name VARCHAR(100) NOT NULL UNIQUE,
    dept_code VARCHAR(20) UNIQUE NOT NULL,
    head_id INT,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (head_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_dept_code (dept_code),
    INDEX idx_is_active (is_active)
);

-- ============================================================================
-- 3. STUDENTS TABLE
-- ============================================================================
CREATE TABLE students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    registration_number VARCHAR(50) UNIQUE NOT NULL,
    enrollment_year INT NOT NULL,
    batch VARCHAR(10),
    dept_id INT NOT NULL,
    date_of_birth DATE,
    address TEXT,
    guardian_name VARCHAR(100),
    guardian_phone VARCHAR(20),
    face_encoding LONGBLOB,
    face_encoding_updated_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (dept_id) REFERENCES departments(dept_id) ON DELETE RESTRICT,
    INDEX idx_registration_number (registration_number),
    INDEX idx_enrollment_year (enrollment_year),
    INDEX idx_dept_id (dept_id),
    INDEX idx_is_active (is_active)
);

-- ============================================================================
-- 4. ACADEMIC STAFF TABLE
-- ============================================================================
CREATE TABLE academic_staff (
    staff_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    staff_code VARCHAR(50) UNIQUE NOT NULL,
    dept_id INT NOT NULL,
    designation VARCHAR(100),
    specialization VARCHAR(100),
    office_location VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (dept_id) REFERENCES departments(dept_id) ON DELETE RESTRICT,
    INDEX idx_staff_code (staff_code),
    INDEX idx_dept_id (dept_id),
    INDEX idx_is_active (is_active)
);

-- ============================================================================
-- 5. LECTURERS TABLE
-- ============================================================================
CREATE TABLE lecturers (
    lecturer_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    lecturer_code VARCHAR(50) UNIQUE NOT NULL,
    dept_id INT NOT NULL,
    specialization VARCHAR(100),
    qualification VARCHAR(255),
    office_location VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (dept_id) REFERENCES departments(dept_id) ON DELETE RESTRICT,
    INDEX idx_lecturer_code (lecturer_code),
    INDEX idx_dept_id (dept_id),
    INDEX idx_is_active (is_active)
);

-- ============================================================================
-- 6. COURSES TABLE
-- ============================================================================
CREATE TABLE courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(150) NOT NULL,
    course_description TEXT,
    credits INT NOT NULL,
    dept_id INT NOT NULL,
    semester INT,
    academic_year VARCHAR(10),
    lecturer_id INT,
    max_enrollment INT DEFAULT 50,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dept_id) REFERENCES departments(dept_id) ON DELETE RESTRICT,
    FOREIGN KEY (lecturer_id) REFERENCES lecturers(lecturer_id) ON DELETE SET NULL,
    INDEX idx_course_code (course_code),
    INDEX idx_dept_id (dept_id),
    INDEX idx_lecturer_id (lecturer_id),
    INDEX idx_academic_year (academic_year),
    INDEX idx_is_active (is_active)
);

-- ============================================================================
-- 7. COURSE ENROLLMENT TABLE (Many-to-Many: Students to Courses)
-- ============================================================================
CREATE TABLE course_enrollment (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'dropped', 'completed', 'failed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (course_id, student_id),
    INDEX idx_course_id (course_id),
    INDEX idx_student_id (student_id),
    INDEX idx_status (status)
);

-- ============================================================================
-- 8. LECTURE SCHEDULES TABLE
-- ============================================================================
CREATE TABLE lecture_schedules (
    schedule_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    classroom_location VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    INDEX idx_course_id (course_id),
    INDEX idx_day_of_week (day_of_week)
);

-- ============================================================================
-- 9. ATTENDANCE TABLE (Face Recognition Attendance)
-- ============================================================================
CREATE TABLE attendance (
    attendance_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    check_in_time TIME,
    check_out_time TIME,
    attendance_status ENUM('present', 'absent', 'late', 'excused') DEFAULT 'absent',
    face_recognition_confidence FLOAT,
    face_image_path VARCHAR(255),
    remarks TEXT,
    recorded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES lecturers(lecturer_id) ON DELETE SET NULL,
    UNIQUE KEY unique_attendance (course_id, student_id, attendance_date),
    INDEX idx_course_id (course_id),
    INDEX idx_student_id (student_id),
    INDEX idx_attendance_date (attendance_date),
    INDEX idx_attendance_status (attendance_status)
);

-- ============================================================================
-- 10. ASSIGNMENTS TABLE
-- ============================================================================
CREATE TABLE assignments (
    assignment_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    assignment_title VARCHAR(150) NOT NULL,
    assignment_description TEXT,
    total_marks INT NOT NULL,
    due_date DATETIME NOT NULL,
    created_by INT NOT NULL,
    attachment_path VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES lecturers(lecturer_id) ON DELETE RESTRICT,
    INDEX idx_course_id (course_id),
    INDEX idx_due_date (due_date),
    INDEX idx_is_active (is_active)
);

-- ============================================================================
-- 11. ASSIGNMENT SUBMISSIONS TABLE
-- ============================================================================
CREATE TABLE assignment_submissions (
    submission_id INT PRIMARY KEY AUTO_INCREMENT,
    assignment_id INT NOT NULL,
    student_id INT NOT NULL,
    submission_date DATETIME NOT NULL,
    submission_file_path VARCHAR(255) NOT NULL,
    submission_text TEXT,
    submission_status ENUM('submitted', 'late', 'not_submitted') DEFAULT 'submitted',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assignment_id) REFERENCES assignments(assignment_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    UNIQUE KEY unique_submission (assignment_id, student_id),
    INDEX idx_assignment_id (assignment_id),
    INDEX idx_student_id (student_id),
    INDEX idx_submission_status (submission_status)
);

-- ============================================================================
-- 12. MARKS/GRADES TABLE
-- ============================================================================
CREATE TABLE marks (
    mark_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    assessment_type ENUM('continuous', 'midterm', 'final', 'project', 'practical') NOT NULL,
    obtained_marks DECIMAL(5, 2),
    total_marks INT NOT NULL,
    percentage DECIMAL(5, 2),
    grade CHAR(2),
    weightage INT,
    remarks TEXT,
    marked_by INT,
    marked_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (marked_by) REFERENCES lecturers(lecturer_id) ON DELETE SET NULL,
    UNIQUE KEY unique_mark (course_id, student_id, assessment_type),
    INDEX idx_course_id (course_id),
    INDEX idx_student_id (student_id),
    INDEX idx_assessment_type (assessment_type)
);

-- ============================================================================
-- 13. FINAL RESULTS TABLE (Calculated from marks)
-- ============================================================================
CREATE TABLE final_results (
    result_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    final_marks DECIMAL(5, 2),
    final_percentage DECIMAL(5, 2),
    final_grade CHAR(2),
    status ENUM('passed', 'failed', 'pending') DEFAULT 'pending',
    result_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    UNIQUE KEY unique_result (course_id, student_id),
    INDEX idx_course_id (course_id),
    INDEX idx_student_id (student_id),
    INDEX idx_status (status)
);

-- ============================================================================
-- 14. ANNOUNCEMENTS TABLE
-- ============================================================================
CREATE TABLE announcements (
    announcement_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    announcement_type ENUM('general', 'department', 'course', 'student') DEFAULT 'general',
    target_dept_id INT,
    target_course_id INT,
    target_student_id INT,
    created_by INT NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    expiry_date DATETIME,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (target_dept_id) REFERENCES departments(dept_id) ON DELETE SET NULL,
    FOREIGN KEY (target_course_id) REFERENCES courses(course_id) ON DELETE SET NULL,
    FOREIGN KEY (target_student_id) REFERENCES students(student_id) ON DELETE SET NULL,
    INDEX idx_announcement_type (announcement_type),
    INDEX idx_priority (priority),
    INDEX idx_is_active (is_active),
    INDEX idx_created_at (created_at)
);

-- ============================================================================
-- 15. ATTENDANCE REPORTS TABLE (Aggregated data for analytics)
-- ============================================================================
CREATE TABLE attendance_reports (
    report_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    total_classes INT DEFAULT 0,
    classes_attended INT DEFAULT 0,
    classes_absent INT DEFAULT 0,
    classes_late INT DEFAULT 0,
    classes_excused INT DEFAULT 0,
    attendance_percentage DECIMAL(5, 2),
    is_compliant BOOLEAN DEFAULT TRUE,
    report_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    UNIQUE KEY unique_report (course_id, student_id, report_date),
    INDEX idx_course_id (course_id),
    INDEX idx_student_id (student_id),
    INDEX idx_attendance_percentage (attendance_percentage)
);

-- ============================================================================
-- 16. AUDIT LOG TABLE (For system auditing)
-- ============================================================================
CREATE TABLE audit_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action_type VARCHAR(100) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action_type (action_type),
    INDEX idx_created_at (created_at)
);

-- ============================================================================
-- 17. SESSIONS TABLE (For user session management)
-- ============================================================================
CREATE TABLE sessions (
    session_id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    logout_time TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_active (is_active)
);

-- ============================================================================
-- 18. PASSWORD RESET TABLE
-- ============================================================================
CREATE TABLE password_reset_tokens (
    token_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    reset_token VARCHAR(255) UNIQUE NOT NULL,
    token_expiry DATETIME NOT NULL,
    is_used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_reset_token (reset_token),
    INDEX idx_is_used (is_used)
);

-- ============================================================================
-- INDEXES FOR PERFORMANCE OPTIMIZATION
-- ============================================================================
CREATE INDEX idx_students_user_id ON students(user_id);
CREATE INDEX idx_academic_staff_user_id ON academic_staff(user_id);
CREATE INDEX idx_lecturers_user_id ON lecturers(user_id);
CREATE INDEX idx_courses_semester ON courses(semester);
CREATE INDEX idx_attendance_check_in ON attendance(check_in_time);
CREATE INDEX idx_marks_percentage ON marks(percentage);
CREATE INDEX idx_announcements_expiry ON announcements(expiry_date);

-- ============================================================================
-- END OF SCHEMA
-- ============================================================================
