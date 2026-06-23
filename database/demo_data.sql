-- ============================================================================
-- Demo Data for Smart Student Management System
-- This script creates demo users for testing the authentication system
-- ============================================================================

-- Demo Users (Admin, Lecturer, Academic Staff, Students)

-- Admin User
-- Username: admin, Password: admin123
INSERT INTO users (username, email, password_hash, full_name, phone, user_role, is_active) 
VALUES ('admin', 'admin@smartsms.local', '$2y$10$YuFaAn/wI7ioIj7G/aJPvO0m1e7TnYlvJf1qH/v3/w5YfJRJ8vI7K', 'System Administrator', '555-0001', 'admin', 1);

-- Lecturer Users
-- Username: lecturer, Password: lecturer123
INSERT INTO users (username, email, password_hash, full_name, phone, user_role, is_active)
VALUES ('lecturer', 'lecturer@smartsms.local', '$2y$10$YC3.F0hPBvC7Ci8RZUNvu.R/YzQcBTgzN7GvBKHJ8q7fRz2JqKI/a', 'Dr. John Smith', '555-0002', 'lecturer', 1);

INSERT INTO users (username, email, password_hash, full_name, phone, user_role, is_active)
VALUES ('lecturer2', 'lecturer2@smartsms.local', '$2y$10$YC3.F0hPBvC7Ci8RZUNvu.R/YzQcBTgzN7GvBKHJ8q7fRz2JqKI/a', 'Prof. Jane Doe', '555-0003', 'lecturer', 1);

-- Academic Staff User
-- Username: academic, Password: academic123
INSERT INTO users (username, email, password_hash, full_name, phone, user_role, is_active)
VALUES ('academic', 'academic@smartsms.local', '$2y$10$5jJqBZe4X6j5D4/G2Cp/xO.4Z8lzqJv6rBnKqZ9Y3Zt6xF7H4vEAm', 'Alice Johnson', '555-0004', 'academic_staff', 1);

-- Student Users
-- Username: student, Password: student123
INSERT INTO users (username, email, password_hash, full_name, phone, user_role, is_active)
VALUES ('student', 'student@smartsms.local', '$2y$10$1bZgr8yc4.7Jz9LqV2XM9.x7f6I8Q3J2.wSm7lN5eQ4H1qR9nKH7W', 'Bob Wilson', '555-0005', 'student', 1);

INSERT INTO users (username, email, password_hash, full_name, phone, user_role, is_active)
VALUES ('student2', 'student2@smartsms.local', '$2y$10$1bZgr8yc4.7Jz9LqV2XM9.x7f6I8Q3J2.wSm7lN5eQ4H1qR9nKH7W', 'Carol Brown', '555-0006', 'student', 1);

-- Get the IDs of users we just created
SET @admin_id = LAST_INSERT_ID() - 5;
SET @lecturer_id = LAST_INSERT_ID() - 4;
SET @lecturer2_id = LAST_INSERT_ID() - 3;
SET @academic_id = LAST_INSERT_ID() - 2;
SET @student_id = LAST_INSERT_ID() - 1;
SET @student2_id = LAST_INSERT_ID();

-- Create demo departments
INSERT INTO departments (dept_name, dept_code, head_id, description, is_active)
VALUES ('Computer Science', 'CS', @admin_id, 'Department of Computer Science', 1);

INSERT INTO departments (dept_name, dept_code, head_id, description, is_active)
VALUES ('Information Technology', 'IT', @admin_id, 'Department of Information Technology', 1);

SET @dept_cs = LAST_INSERT_ID() - 1;
SET @dept_it = LAST_INSERT_ID();

-- Create demo courses
INSERT INTO courses (course_code, course_name, description, lecturer_id, dept_id, semester, credits, is_active)
VALUES ('CS101', 'Introduction to Programming', 'Learn the basics of programming with Python', @lecturer_id, @dept_cs, '1', 3, 1);

INSERT INTO courses (course_code, course_name, description, lecturer_id, dept_id, semester, credits, is_active)
VALUES ('CS201', 'Data Structures', 'Advanced data structures and algorithms', @lecturer_id, @dept_cs, '2', 4, 1);

INSERT INTO courses (course_code, course_name, description, lecturer_id, dept_id, semester, credits, is_active)
VALUES ('IT101', 'Web Development', 'Building responsive web applications', @lecturer2_id, @dept_it, '1', 3, 1);

SET @course_cs101 = LAST_INSERT_ID() - 2;
SET @course_cs201 = LAST_INSERT_ID() - 1;
SET @course_it101 = LAST_INSERT_ID();

-- Create student records
INSERT INTO students (user_id, enrollment_date, status)
VALUES (@student_id, NOW(), 'active');

INSERT INTO students (user_id, enrollment_date, status)
VALUES (@student2_id, NOW(), 'active');

SET @student_rec_id = LAST_INSERT_ID() - 1;
SET @student2_rec_id = LAST_INSERT_ID();

-- Enroll students in courses
INSERT INTO course_enrollment (student_id, course_id, enrollment_date, status)
VALUES (@student_rec_id, @course_cs101, NOW(), 'enrolled');

INSERT INTO course_enrollment (student_id, course_id, enrollment_date, status)
VALUES (@student_rec_id, @course_cs201, NOW(), 'enrolled');

INSERT INTO course_enrollment (student_id, course_id, enrollment_date, status)
VALUES (@student2_rec_id, @course_it101, NOW(), 'enrolled');

-- Create demo lecturers
INSERT INTO lecturers (user_id, specialization, qualifications, experience_years)
VALUES (@lecturer_id, 'Software Engineering', 'PhD in Computer Science', 10);

INSERT INTO lecturers (user_id, specialization, qualifications, experience_years)
VALUES (@lecturer2_id, 'Web Technologies', 'Master in IT', 8);

-- Create demo academic staff
INSERT INTO academic_staff (user_id, position, department_id)
VALUES (@academic_id, 'Academic Coordinator', @dept_cs);

-- ============================================================================
-- NOTE: Password hashes created with:
-- password_hash('admin123', PASSWORD_BCRYPT)
-- password_hash('lecturer123', PASSWORD_BCRYPT)
-- password_hash('academic123', PASSWORD_BCRYPT)
-- password_hash('student123', PASSWORD_BCRYPT)
-- ============================================================================
