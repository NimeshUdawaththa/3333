# Quick Start Guide - PHP Authentication System

## ⚡ 5-Minute Setup

### Step 1: Import Demo Data (Optional but Recommended)

In PhpMyAdmin:
1. Go to `http://localhost/phpmyadmin`
2. Select the `smart_student_management` database
3. Click the **Import** tab
4. Upload `database/demo_data.sql`
5. Click **Go**

**OR** via command line:
```bash
cd C:\xampp\mysql\bin
mysql -u root smart_student_management < "C:\Users\Nimesh\Desktop\Smart-Student-Management-System\database\demo_data.sql"
```

### Step 2: Access the Login Page

Open your browser and go to:
```
http://localhost/Smart-Student-Management-System/frontend/auth/login.php
```

### Step 3: Login with Demo Account

Choose any of these credentials:

| Role | Username | Password |
|------|----------|----------|
| 👨‍💼 Admin | `admin` | `admin123` |
| 👨‍🏫 Lecturer | `lecturer` | `lecturer123` |
| 👩‍💻 Academic Staff | `academic` | `academic123` |
| 👨‍🎓 Student | `student` | `student123` |

### Done! 🎉

You now have a fully functional PHP authentication system with role-based access control.

---

## 📦 What You Get

✅ **Complete Authentication System**
- Login & Logout
- Session Management
- Password Management

✅ **4 Role-Based Dashboards**
- Admin Dashboard - System management
- Lecturer Dashboard - Course & assignment management
- Student Dashboard - Courses & grades
- Academic Staff Dashboard - Enrollment & results

✅ **Security Features**
- Bcrypt password hashing
- Session timeout (30 mins)
- RBAC middleware
- Audit logging
- SQL injection prevention

✅ **User Interface**
- Bootstrap 5 responsive design
- Professional styling
- Easy navigation
- Mobile-friendly

---

## 🗂️ Project Structure

```
Smart-Student-Management-System/
│
├── backend/
│   ├── auth/
│   │   └── auth.php                 # Core authentication logic
│   ├── middleware/
│   │   └── auth_middleware.php      # Role-based access control
│   └── api/auth/
│       ├── login_process.php        # Handles login
│       └── logout.php               # Handles logout
│
├── config/
│   ├── db.php                       # Database connection
│   └── session_config.php           # Session settings
│
├── frontend/
│   ├── auth/
│   │   └── login.php                # Login page
│   ├── dashboard/
│   │   ├── admin_dashboard.php
│   │   ├── lecturer_dashboard.php
│   │   ├── student_dashboard.php
│   │   └── academic_dashboard.php
│   ├── profile/
│   │   ├── profile.php
│   │   └── change_password.php
│   ├── components/
│   │   └── navbar.php               # Navigation bar
│   └── error/
│       └── 403.php                  # Access denied page
│
└── database/
    ├── database.sql                 # Main schema
    └── demo_data.sql                # Demo users & courses
```

---

## 🔑 Key Features

### 1. **Login Page** (`frontend/auth/login.php`)
- Username or email login
- Beautiful UI with gradient background
- Demo credentials displayed
- Error messages
- Remember me option

### 2. **Admin Dashboard** (`frontend/dashboard/admin_dashboard.php`)
- System statistics
- User management
- Department management
- Audit logs
- Full system control

### 3. **Lecturer Dashboard** (`frontend/dashboard/lecturer_dashboard.php`)
- My courses
- Student enrollment
- Assignment management
- Attendance tracking
- Mark management

### 4. **Student Dashboard** (`frontend/dashboard/student_dashboard.php`)
- Enrolled courses
- Pending assignments
- GPA tracking
- Attendance rate
- Course materials

### 5. **Academic Staff Dashboard** (`frontend/dashboard/academic_dashboard.php`)
- Student management
- Course management
- Enrollment handling
- Result tracking
- System analytics

---

## 📝 How to Use

### Login to the System
```
URL: http://localhost/Smart-Student-Management-System/frontend/auth/login.php
```

### After Login
- You'll be automatically redirected to your role-specific dashboard
- Use the sidebar to navigate
- Click on your name to access profile
- Click "Logout" to exit

### Change Your Password
1. Click on your profile dropdown
2. Select "Change Password"
3. Enter current password
4. Enter new password (min 6 chars)
5. Confirm new password
6. Click "Change Password"

---

## 🔒 Security Highlights

- **Password Security**: Bcrypt hashing with salt
- **Session Security**: 30-minute timeout + activity tracking
- **Access Control**: Role-based middleware protection
- **Input Validation**: Prepared statements + sanitization
- **Audit Logging**: All logins tracked
- **SQL Injection Prevention**: Parameterized queries
- **XSS Prevention**: HTML escaping on output

---

## 🛠️ Technologies Used

- **Backend**: PHP 8+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Security**: bcrypt, Session Management
- **Architecture**: Object-Oriented PHP

---

## 📱 Responsive Design

The authentication system is fully responsive:
- ✅ Desktop
- ✅ Tablet
- ✅ Mobile

Try resizing your browser to see the responsive sidebar navigation!

---

## 🔍 Testing the System

### Test Login with Different Roles

1. **Login as Admin**
   - Username: `admin`
   - Password: `admin123`
   - View: Admin Dashboard with full system access

2. **Login as Lecturer**
   - Username: `lecturer`
   - Password: `lecturer123`
   - View: Lecturer Dashboard with course management

3. **Login as Student**
   - Username: `student`
   - Password: `student123`
   - View: Student Dashboard with course enrollment

4. **Login as Academic Staff**
   - Username: `academic`
   - Password: `academic123`
   - View: Academic Dashboard with enrollment management

### Test Session Management
- Login and wait 30 minutes
- Session will automatically expire
- You'll be redirected to login page

### Test Role-Based Access
- Try accessing URLs directly for other roles
- You'll get a 403 Forbidden error
- This demonstrates RBAC protection

### Test Password Change
- Login
- Go to Profile → Change Password
- Change your password
- Try logging in with old password (should fail)
- Login with new password (should work)

---

## 📚 For Developers

### Include Authentication in Your Pages

```php
<?php
require_once('config/db.php');
require_once('backend/middleware/auth_middleware.php');

// Require user to be logged in
AuthMiddleware::requireLogin();

// Require specific role
AuthMiddleware::requireRole('admin');

// Get user info
$user = AuthMiddleware::getCurrentUser($conn);
echo $user['full_name'];
?>
```

### Add New Users to Database

```sql
INSERT INTO users (username, email, password_hash, full_name, user_role, is_active)
VALUES ('john', 'john@example.com', PASSWORD_HASH_HERE, 'John Doe', 'student', 1);
```

Generate password hash using: `password_hash('password', PASSWORD_BCRYPT)`

---

## ✨ Features Included

✅ User Registration (Backend ready)
✅ Login/Logout
✅ Session Management
✅ Password Change
✅ Role-Based Access Control
✅ 4 Different Dashboards
✅ User Profile
✅ Audit Logging
✅ Error Handling
✅ Responsive Design
✅ Security Best Practices
✅ Bootstrap 5 UI

---

## 🚀 Next Steps

1. **Create Additional Pages**: Use the authentication middleware to protect new pages
2. **Customize Dashboards**: Modify dashboard content for your needs
3. **Add Features**: Extend with more role-specific features
4. **Database**: Add more users/courses/departments as needed
5. **Styling**: Customize colors and branding

---

## 💡 Tips

- Use `AuthMiddleware::requireLogin()` at the top of pages that need protection
- Always use prepared statements to prevent SQL injection
- Sanitize all user output with `htmlspecialchars()`
- Session auto-expires after 30 minutes of inactivity
- All passwords are hashed with bcrypt

---

## 📞 Support

For detailed documentation, see:
- `docs/AUTHENTICATION_GUIDE.md` - Complete reference guide
- `database/SCHEMA_DOCUMENTATION.md` - Database details
- `docs/ARCHITECTURE.md` - System architecture

---

**Authentication System v1.0** 
✅ Ready for Production Use
