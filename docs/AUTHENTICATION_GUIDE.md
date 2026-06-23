# PHP Authentication System - Complete Documentation

## Overview
This document provides a comprehensive guide to the PHP Authentication System for the Smart Student Management System, including login, logout, session management, and role-based access control.

---

## 📋 Features

### 1. **User Authentication**
- Login with username or email
- Secure password hashing using bcrypt
- Session-based authentication
- Session timeout management (30 minutes)

### 2. **Session Management**
- Automatic session creation on login
- Session tracking and timeout
- Activity-based session extension
- Secure session cookies

### 3. **Role-Based Access Control (RBAC)**
- Four user roles: Admin, Academic Staff, Lecturer, Student
- Role-based redirection to specific dashboards
- Middleware-based permission checking
- Role-specific navigation and features

### 4. **User Management**
- User registration (with role assignment)
- Password change functionality
- User profile management
- Audit logging for security tracking

---

## 📁 File Structure

```
Smart-Student-Management-System/
├── backend/
│   ├── auth/
│   │   └── auth.php                    # Authentication class
│   ├── middleware/
│   │   └── auth_middleware.php         # RBAC middleware
│   └── api/auth/
│       ├── login_process.php           # Login handler
│       └── logout.php                  # Logout handler
├── config/
│   ├── db.php                          # Database connection
│   └── session_config.php              # Session configuration
└── frontend/
    ├── auth/
    │   └── login.php                   # Login page
    ├── dashboard/
    │   ├── index.php                   # Dashboard redirect
    │   ├── admin_dashboard.php         # Admin dashboard
    │   ├── lecturer_dashboard.php      # Lecturer dashboard
    │   ├── student_dashboard.php       # Student dashboard
    │   └── academic_dashboard.php      # Academic staff dashboard
    ├── profile/
    │   ├── profile.php                 # User profile
    │   └── change_password.php         # Password change
    ├── components/
    │   └── navbar.php                  # Navigation component
    └── error/
        └── 403.php                     # Access denied page
```

---

## 🔐 Demo Credentials

### Admin
```
Username: admin
Password: admin123
```

### Lecturer
```
Username: lecturer
Password: lecturer123
```

### Academic Staff
```
Username: academic
Password: academic123
```

### Student
```
Username: student
Password: student123
```

---

## 🚀 Getting Started

### Step 1: Setup Database

1. Import the main database schema:
```bash
mysql -u root < database/database.sql
```

2. Import demo data (optional):
```bash
mysql -u root smart_student_management < database/demo_data.sql
```

### Step 2: Configure Connection

Update `config/db.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smart_student_management');
```

### Step 3: Place Files in XAMPP

Copy the entire project to:
```
C:\xampp\htdocs\Smart-Student-Management-System\
```

### Step 4: Start Services

1. Open XAMPP Control Panel
2. Start Apache
3. Start MySQL

### Step 5: Access the System

Open your browser and navigate to:
```
http://localhost/Smart-Student-Management-System/frontend/auth/login.php
```

---

## 💻 Usage Guide

### Logging In

1. Go to login page
2. Enter username (or email) and password
3. Click "Login"
4. You'll be redirected to your role-specific dashboard

### Role-Based Dashboards

#### **Admin Dashboard**
- View system statistics
- Manage users
- Manage departments
- Manage courses
- View audit logs

#### **Lecturer Dashboard**
- View assigned courses
- View enrolled students
- Manage assignments
- Track attendance
- Manage marks

#### **Student Dashboard**
- View enrolled courses
- View assignments
- View marks and GPA
- Track attendance
- Submit assignments

#### **Academic Staff Dashboard**
- Manage students
- Manage courses
- Handle enrollments
- View results
- Monitor system statistics

### Changing Password

1. Click on Profile in the navigation menu
2. Click "Change Password"
3. Enter current password
4. Enter new password (min 6 characters)
5. Confirm new password
6. Click "Change Password"

### Logging Out

Click "Logout" in the user menu dropdown

---

## 🔑 API Reference

### Authentication Class (`backend/auth/auth.php`)

#### Login
```php
$auth = new Auth($conn);
$result = $auth->login($username, $password);

// Returns:
// [
//     'success' => true/false,
//     'message' => 'Login message',
//     'user' => [user data]
// ]
```

#### Register
```php
$result = $auth->register([
    'username' => 'john',
    'email' => 'john@example.com',
    'password' => 'secure123',
    'full_name' => 'John Doe',
    'role' => 'student'  // optional
]);
```

#### Change Password
```php
$result = $auth->changePassword($user_id, $old_password, $new_password);
```

### Middleware (`backend/middleware/auth_middleware.php`)

#### Check Login
```php
AuthMiddleware::isLoggedIn()  // Returns boolean
```

#### Require Login
```php
AuthMiddleware::requireLogin();  // Redirects if not logged in
```

#### Require Specific Role
```php
AuthMiddleware::requireRole('admin');  // Redirects if wrong role
AuthMiddleware::requireRole(['admin', 'lecturer']);  // Multiple roles
```

#### Get User Info
```php
$user_id = AuthMiddleware::getUserId();
$user_role = AuthMiddleware::getUserRole();
$user = AuthMiddleware::getCurrentUser($conn);
```

#### Check Role Permission
```php
AuthMiddleware::hasRole('admin');  // Single role
AuthMiddleware::hasAnyRole(['admin', 'lecturer']);  // Multiple roles
```

---

## 📝 Including in Your Pages

To protect a page with authentication:

```php
<?php
require_once(__DIR__ . '/config/db.php');
require_once(__DIR__ . '/backend/middleware/auth_middleware.php');

// Check if user is logged in
AuthMiddleware::requireLogin();

// Or check for specific role
AuthMiddleware::requireRole('admin');

// Now you can use user info
$user_id = AuthMiddleware::getUserId();
$user_role = AuthMiddleware::getUserRole();
$user = AuthMiddleware::getCurrentUser($conn);
?>
```

---

## 🗄️ Database Tables

### users
Main user table with all role types

**Key Fields:**
- `user_id` - Primary key
- `username` - Unique username
- `email` - Unique email
- `password_hash` - Bcrypt hashed password
- `user_role` - admin, academic_staff, lecturer, student
- `is_active` - Account status

### students
Student-specific information

**Key Fields:**
- `student_id` - Primary key
- `user_id` - Foreign key to users
- `enrollment_date` - Date of enrollment
- `status` - active, inactive, graduated

### lecturers
Lecturer-specific information

**Key Fields:**
- `lecturer_id` - Primary key
- `user_id` - Foreign key to users
- `specialization` - Area of expertise
- `qualifications` - Degrees/credentials
- `experience_years` - Years of experience

### academic_staff
Academic staff information

**Key Fields:**
- `staff_id` - Primary key
- `user_id` - Foreign key to users
- `position` - Job title
- `department_id` - Department assignment

### audit_logs
Tracks login attempts and other actions

---

## 🔒 Security Features

### 1. **Password Security**
- Bcrypt hashing (PASSWORD_BCRYPT)
- Minimum 6 characters required
- Passwords never stored in plain text

### 2. **Session Security**
- 30-minute session timeout
- HTTPOnly cookies
- SameSite=Strict cookie policy
- Session activity tracking

### 3. **Access Control**
- Middleware-based permission checking
- Role-based redirection
- 403 error page for unauthorized access

### 4. **Input Validation**
- Username/Email validation
- Password strength checking
- SQL injection prevention (prepared statements)
- XSS prevention (htmlspecialchars)

### 5. **Audit Logging**
- Login attempt tracking
- Audit log storage
- Security event recording

---

## 🛠️ Customization

### Add New Role

1. Update database `user_role` ENUM in `users` table:
```sql
ALTER TABLE users MODIFY user_role ENUM('admin', 'academic_staff', 'lecturer', 'student', 'new_role');
```

2. Create dashboard page for new role
3. Update role switch in `login_process.php`
4. Update middleware role checking

### Change Session Timeout

Edit `config/session_config.php`:
```php
define('SESSION_TIMEOUT', 3600);  // 1 hour in seconds
```

### Change Password Requirements

Edit `backend/auth/auth.php` in the `register()` method

### Customize Login Page

Edit `frontend/auth/login.php` to match your brand

---

## ❓ Troubleshooting

### Login Not Working
- Check database connection in `config/db.php`
- Verify MySQL is running
- Check user exists in database with correct password hash

### Session Expiring Too Fast
- Increase `SESSION_TIMEOUT` in `session_config.php`
- Check browser cookie settings

### Permission Denied
- Verify user role in database
- Check RBAC middleware is properly included
- Verify correct role is being required

### Password Change Not Working
- Ensure old password is correct
- Check new password meets minimum length (6 chars)
- Verify user permissions to change password

---

## 📋 Notes

- All timestamps use `CURRENT_TIMESTAMP`
- Bcrypt hash cost factor is 10 (default)
- Session storage uses PHP's default (file-based)
- For production, consider storing sessions in database

---

## 📞 Support

For issues or questions about the authentication system, refer to the database schema documentation in `database/SCHEMA_DOCUMENTATION.md`

---

**Last Updated:** June 2026
**Version:** 1.0
**Status:** Production Ready
