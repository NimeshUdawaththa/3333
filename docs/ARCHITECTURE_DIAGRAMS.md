# Authentication System - Architecture & Flow Diagrams

## 1. Authentication Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                    USER AUTHENTICATION FLOW                          │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────┐
│ User Opens  │
│ Login Page  │
└──────┬──────┘
       │
       ▼
┌─────────────────────────────────────────────────────────────────┐
│ frontend/auth/login.php                                         │
│ - Display login form                                            │
│ - Accept username/email and password                            │
└──────┬──────────────────────────────────────────────────────────┘
       │
       │ POST request
       ▼
┌─────────────────────────────────────────────────────────────────┐
│ backend/api/auth/login_process.php                              │
│ - Receive credentials                                           │
│ - Validate input                                                │
└──────┬──────────────────────────────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────────────────────────────┐
│ backend/auth/auth.php                                           │
│ - Create Auth instance                                          │
│ - Call auth.login($username, $password)                         │
└──────┬──────────────────────────────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────────────────────────────┐
│ Database Query                                                  │
│ SELECT * FROM users WHERE username=? OR email=?                │
│ AND is_active=1                                                 │
└──────┬──────────────────────────────────────────────────────────┘
       │
       ├─── User Not Found ──┐
       │                     │
       │                     ▼
       │            Return: success=false
       │            Message: "Invalid credentials"
       │
       └─── User Found ──┐
                         │
                         ▼
            ┌──────────────────────────┐
            │ Verify Password          │
            │ password_verify(pwd,     │
            │   password_hash)         │
            └──────┬────────────────────┘
                   │
                   ├─── Password Incorrect ──┐
                   │                         │
                   │                         ▼
                   │                Log Failed Attempt
                   │                Return: success=false
                   │
                   └─── Password Correct ──┐
                                           │
                                           ▼
                            ┌──────────────────────────┐
                            │ Create Session           │
                            │ $_SESSION['user_id']     │
                            │ $_SESSION['user_role']   │
                            │ $_SESSION['username']    │
                            │ $_SESSION['full_name']   │
                            │ $_SESSION['email']       │
                            └──────┬────────────────────┘
                                   │
                                   ▼
                        ┌──────────────────────────┐
                        │ Log Successful Attempt   │
                        │ INSERT INTO audit_logs   │
                        └──────┬────────────────────┘
                               │
                               ▼
                    ┌──────────────────────────┐
                    │ Check User Role          │
                    └──────┬────────────────────┘
                           │
        ┌──────────┬────────┼────────┬──────────┐
        │          │        │        │          │
    admin     academic   lecturer  student   unknown
        │          │        │        │          │
        ▼          ▼        ▼        ▼          ▼
    admin_       academic_ lecturer_ student_  error
    dashboard    dashboard dashboard dashboard
```

---

## 2. Role-Based Access Control Flow

```
┌──────────────────────────────────────────────────────────────────┐
│              ROLE-BASED ACCESS CONTROL (RBAC)                    │
└──────────────────────────────────────────────────────────────────┘

Page Request
     │
     ▼
┌──────────────────────────────────────────────────────────────────┐
│ Include: middleware/auth_middleware.php                          │
│ Call: AuthMiddleware::requireLogin()                             │
└──────┬───────────────────────────────────────────────────────────┘
       │
       ├─── Session Exists? ──┐
       │                      │
       │ No                   Yes
       │  │                    │
       │  ▼                    ▼
       │ Redirect          Check Role
       │ to login        requireRole('admin')
       │                    │
       │              ┌─────┼─────┐
       │              │           │
       │          Role OK    Role Wrong
       │              │           │
       │              ▼           ▼
       │          Load Page   Redirect
       │          Continue    to 403
       │
       ▼ (After requireLogin passes)
Display Page with User Data
```

---

## 3. Session Lifecycle

```
┌──────────────────────────────────────────────────────────────────┐
│                    SESSION LIFECYCLE                              │
└──────────────────────────────────────────────────────────────────┘

User Activity
    │
    ▼
┌─────────────────────────────────┐
│ Session Started                 │
│ last_activity = NOW             │
│ timeout = 30 minutes            │
└──────┬──────────────────────────┘
       │
       ├─────────────┐
       │             │
    Active          Inactive
    Page            (No activity
    Views           > 30 min)
       │             │
       ▼             ▼
  Update        Session Expired
  last_activity Redirect to
  = NOW         login page

┌────────────────────────────────────────────────────────────────┐
│ Check on Every Page Load:                                      │
│ if (time() - last_activity > SESSION_TIMEOUT)                 │
│   → session_destroy()                                          │
│   → Redirect to login                                          │
└────────────────────────────────────────────────────────────────┘
```

---

## 4. Database Schema - Authentication Related

```
┌──────────────────────────────────────────────────────────────┐
│                         USERS TABLE                           │
├──────────────────────────────────────────────────────────────┤
│ user_id (PK)           │ Auto-increment ID                   │
│ username (UNIQUE)      │ Login username                      │
│ email (UNIQUE)         │ Login email                         │
│ password_hash          │ Bcrypt hashed password              │
│ full_name              │ User display name                   │
│ user_role              │ admin|lecturer|student|academic...  │
│ is_active              │ Account status (1=active, 0=inactive)
│ created_at             │ Account creation timestamp          │
│ updated_at             │ Last modification timestamp         │
└──────────────────────────────────────────────────────────────┘

    │
    ├─────── Has Records In ─────────┐
    │                                 │
    ▼                                 ▼
┌──────────────┐            ┌──────────────────┐
│ STUDENTS     │            │ LECTURERS        │
├──────────────┤            ├──────────────────┤
│ student_id   │            │ lecturer_id      │
│ user_id (FK) │            │ user_id (FK)     │
│ enrollment.. │            │ specialization   │
│ status       │            │ qualifications   │
└──────────────┘            │ experience_years │
                            └──────────────────┘

    │
    └────── Logged In ──────────┐
                                 ▼
                        ┌──────────────────┐
                        │ AUDIT_LOGS       │
                        ├──────────────────┤
                        │ log_id           │
                        │ user_id (FK)     │
                        │ action           │
                        │ details          │
                        │ created_at       │
                        └──────────────────┘
```

---

## 5. File Structure & Dependencies

```
┌─────────────────────────────────────────────────────────────────┐
│                     AUTHENTICATION SYSTEM                        │
└─────────────────────────────────────────────────────────────────┘

CONFIGURATION LAYER
├─ config/db.php
│  └─ Database connection
│
└─ config/session_config.php
   └─ Session initialization

BUSINESS LOGIC LAYER
├─ backend/auth/auth.php
│  ├─ login()
│  ├─ register()
│  ├─ logout()
│  └─ changePassword()
│
└─ backend/middleware/auth_middleware.php
   ├─ isLoggedIn()
   ├─ requireLogin()
   ├─ requireRole()
   └─ getCurrentUser()

API LAYER
├─ backend/api/auth/login_process.php
│  └─ Handles login submission
│
└─ backend/api/auth/logout.php
   └─ Handles logout

UI LAYER
├─ frontend/auth/login.php
│  └─ Login form
│
├─ frontend/dashboard/
│  ├─ index.php (redirect)
│  ├─ admin_dashboard.php
│  ├─ lecturer_dashboard.php
│  ├─ student_dashboard.php
│  └─ academic_dashboard.php
│
├─ frontend/profile/
│  ├─ profile.php
│  └─ change_password.php
│
└─ frontend/components/
   └─ navbar.php
```

---

## 6. Request Flow Diagram

```
┌────────────────────────────────────────────────────────────────┐
│                      REQUEST FLOW                              │
└────────────────────────────────────────────────────────────────┘

User Request
     │
     ▼
┌──────────────────────────────────────────────────────────┐
│ Requested Page                                           │
│ (e.g., dashboard/admin_dashboard.php)                    │
└──────┬───────────────────────────────────────────────────┘
       │
       ▼
┌──────────────────────────────────────────────────────────┐
│ Include: config/db.php                                   │
│ - Database connection                                    │
│ - Base URLs                                              │
└──────┬───────────────────────────────────────────────────┘
       │
       ▼
┌──────────────────────────────────────────────────────────┐
│ Include: config/session_config.php                       │
│ - Session initialization                                 │
│ - Timeout check                                          │
└──────┬───────────────────────────────────────────────────┘
       │
       ▼
┌──────────────────────────────────────────────────────────┐
│ Include: middleware/auth_middleware.php                  │
│ - Load AuthMiddleware class                              │
└──────┬───────────────────────────────────────────────────┘
       │
       ▼
┌──────────────────────────────────────────────────────────┐
│ Call: AuthMiddleware::requireRole('admin')               │
│ - Check if logged in                                     │
│ - Check if correct role                                  │
└──────┬───────────────────────────────────────────────────┘
       │
       ├─── Authorization Failed ──────┐
       │                               │
       │                               ▼
       │                      Redirect to 403 or login
       │
       └─── Authorization Success ────┐
                                       │
                                       ▼
                            Load and Display Page
                            with User Data
```

---

## 7. Role-Specific Features

```
┌──────────────────────────────────────────────────────────────┐
│              ROLE-SPECIFIC FEATURE MATRIX                    │
└──────────────────────────────────────────────────────────────┘

                ADMIN     LECTURER   ACADEMIC   STUDENT
               ─────────────────────────────────────────
Login           ✓         ✓          ✓          ✓
Logout          ✓         ✓          ✓          ✓
Change Password ✓         ✓          ✓          ✓
View Profile    ✓         ✓          ✓          ✓

Dashboard       ✓         ✓          ✓          ✓
Manage Users    ✓         ✗          ✗          ✗
Manage Depts    ✓         ✗          ✗          ✗
Audit Logs      ✓         ✗          ✗          ✗

My Courses      ✗         ✓          ✗          ✓
Assignments     ✗         ✓          ✗          ✓
Marks           ✗         ✓          ✗          ✓
Attendance      ✗         ✓          ✗          ✓

Enrollments     ✗         ✗          ✓          ✗
Student Mgmt    ✗         ✗          ✓          ✗
Results         ✗         ✗          ✓          ✗
```

---

## 8. Security Layers

```
┌──────────────────────────────────────────────────────────────┐
│                    SECURITY LAYERS                           │
└──────────────────────────────────────────────────────────────┘

Layer 1: Database
├─ Prepared Statements (Prevent SQL Injection)
├─ Password Hashing (Bcrypt)
└─ Audit Logging

Layer 2: Session
├─ HTTPOnly Cookies
├─ SameSite=Strict
├─ 30-minute Timeout
└─ Activity Tracking

Layer 3: Authentication
├─ Username/Email Validation
├─ Password Verification
├─ Failed Login Tracking
└─ Account Status Check

Layer 4: Authorization
├─ Role-Based Access Control
├─ Middleware Checking
├─ Permission Enforcement
└─ 403 Error Handling

Layer 5: Input/Output
├─ Input Validation
├─ HTML Escaping (htmlspecialchars)
├─ Type Checking
└─ XSS Prevention
```

---

## 9. Session State Transitions

```
┌──────────────────────────────────────────────────────────────┐
│              SESSION STATE MACHINE                           │
└──────────────────────────────────────────────────────────────┘

                    ┌──────────────┐
                    │   NOT LOGGED │
                    │   IN (NIL)   │
                    └────────┬─────┘
                             │
                             │ Valid Login
                             ▼
                    ┌──────────────┐
                    │   LOGGED IN  │  ◄─────────┐
                    │   (ACTIVE)   │            │
                    └────┬────┬────┘            │
                         │    │                │
        Page View        │    │ No Activity   │
        (Reset Timer)    │    │ (> 30 min)   │
                         │    │                │
                         │    ▼                │
                         │  ┌──────────────┐  │
                         │  │   EXPIRED    │──┘
                         │  │  (TIMEOUT)   │
                         │  └──────────────┘
                         │
                    Logout
                         │
                         ▼
                    ┌──────────────┐
                    │  LOGGED OUT  │
                    │  (DESTROYED) │
                    └──────────────┘
```

---

## 10. Integration Points

```
┌──────────────────────────────────────────────────────────────┐
│            SYSTEM INTEGRATION POINTS                         │
└──────────────────────────────────────────────────────────────┘

Frontend Pages
     │
     ├─→ Include middleware/auth_middleware.php
     │   │
     │   └─→ Calls config/session_config.php
     │
     └─→ Include config/db.php
         │
         └─→ Database connection
             │
             ├─→ Query users table
             ├─→ Query role-specific tables
             └─→ Insert to audit_logs

API Endpoints
     │
     ├─→ Login Process
     │   └─→ backend/api/auth/login_process.php
     │       ├─→ backend/auth/auth.php
     │       └─→ config/db.php
     │
     └─→ Logout Process
         └─→ backend/api/auth/logout.php
             └─→ backend/auth/auth.php

Database
     │
     ├─→ users (authentication)
     ├─→ students (student info)
     ├─→ lecturers (lecturer info)
     ├─→ academic_staff (staff info)
     └─→ audit_logs (tracking)
```

---

This authentication system provides a complete, secure, and role-based access control solution for the Smart Student Management System!
