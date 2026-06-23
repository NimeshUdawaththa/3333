# XAMPP Setup Guide
## Smart Student Management System

---

## Prerequisites

- XAMPP installed (Apache, MySQL, PHP 8)
- Python 3.8+ installed
- VS Code or similar IDE
- Git (optional, for version control)
- Modern web browser (Chrome, Firefox, Edge)

---

## Step 1: XAMPP Installation & Configuration

### 1.1 Download and Install XAMPP
- Download from: https://www.apachefriends.org/
- Install XAMPP (recommended: C:\xampp or /Applications/XAMPP)
- Ensure Apache and MySQL are configured to auto-start

### 1.2 Verify Installation
```bash
# Start XAMPP Control Panel
# Click "Start" for Apache and MySQL services
# Check System Tray for green checkmarks
```

### 1.3 Access Control Panel
- Apache: http://localhost
- PhpMyAdmin: http://localhost/phpmyadmin
- MySQL Admin: http://localhost/security/index.php

---

## Step 2: Project Setup

### 2.1 Create Project Directory
```bash
# Navigate to XAMPP htdocs
cd C:\xampp\htdocs  # Windows
# or
cd /Applications/XAMPP/htdocs  # macOS

# Create project folder
mkdir Smart-Student-Management-System
cd Smart-Student-Management-System
```

### 2.2 Copy Project Files
- Extract all project files into this directory
- Folder structure should look like:
```
htdocs/Smart-Student-Management-System/
├── frontend/
├── backend/
├── ai_module/
├── database/
├── docs/
└── README.md
```

---

## Step 3: MySQL Database Setup

### 3.1 Create Database User
```bash
# Open PhpMyAdmin
http://localhost/phpmyadmin

# Login with:
Username: root
Password: (leave blank, default in XAMPP)
```

### 3.2 Import Database Schema
```bash
1. In PhpMyAdmin, click "Import"
2. Select file: database/database.sql
3. Click "Go"
4. Database "smart_student_management" will be created
5. All 18 tables will be created
```

### 3.3 Create Application User (Optional, Recommended)
```sql
-- In PhpMyAdmin SQL tab, execute:
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'App@Secure123';
GRANT ALL PRIVILEGES ON smart_student_management.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3.4 Verify Tables
```bash
# In PhpMyAdmin
1. Select "smart_student_management" database
2. You should see 18 tables:
   - users
   - departments
   - students
   - lecturers
   - academic_staff
   - courses
   - course_enrollment
   - lecture_schedules
   - attendance
   - assignments
   - assignment_submissions
   - marks
   - final_results
   - announcements
   - attendance_reports
   - audit_logs
   - sessions
   - password_reset_tokens
```

---

## Step 4: Backend Configuration (PHP)

### 4.1 Create Configuration File
Create file: `backend/config/database.php`

```php
<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // or 'app_user'
define('DB_PASS', '');      // or 'App@Secure123'
define('DB_NAME', 'smart_student_management');

// Connection
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Set charset
$db->set_charset("utf8mb4");

define('DB_CHARSET', 'utf8mb4');
?>
```

### 4.2 Create Constants File
Create file: `backend/config/constants.php`

```php
<?php
// Application Settings
define('APP_NAME', 'Smart Student Management System');
define('APP_VERSION', '1.0.0');

// Paths
define('BASE_URL', 'http://localhost/Smart-Student-Management-System');
define('BACKEND_URL', BASE_URL . '/backend');
define('FRONTEND_URL', BASE_URL . '/frontend');
define('UPLOAD_DIR', dirname(__FILE__) . '/../../uploads');
define('FACE_ENCODING_DIR', UPLOAD_DIR . '/face_encodings');
define('FACE_IMAGE_DIR', UPLOAD_DIR . '/face_images');

// API Settings
define('API_TIMEOUT', 30);
define('MAX_UPLOAD_SIZE', 50 * 1024 * 1024); // 50MB

// Authentication
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('JWT_SECRET', 'your_secret_key_here_change_in_production');
define('JWT_ALGORITHM', 'HS256');
define('JWT_EXPIRY', 900); // 15 minutes

// Face Recognition
define('FACE_RECOGNITION_CONFIDENCE_THRESHOLD', 0.6);
define('FACE_RECOGNITION_API', 'http://localhost:5000');

// Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_STAFF', 'academic_staff');
define('ROLE_LECTURER', 'lecturer');
define('ROLE_STUDENT', 'student');

// Attendance
define('MIN_ATTENDANCE_PERCENTAGE', 75);

// Grades
$GRADES = [
    [90, 100, 'A'],
    [80, 89, 'B+'],
    [70, 79, 'B'],
    [60, 69, 'C+'],
    [50, 59, 'C'],
    [40, 49, 'D'],
    [0, 39, 'F']
];

?>
```

### 4.3 Create Upload Directories
```bash
# Create necessary directories
mkdir uploads
mkdir uploads/face_encodings
mkdir uploads/face_images
mkdir uploads/assignments
mkdir uploads/submissions
mkdir uploads/documents

# Set permissions (Linux/macOS)
chmod 755 uploads
chmod 755 uploads/face_encodings
chmod 755 uploads/face_images
```

### 4.4 Test PHP Configuration
Create file: `backend/test_config.php`

```php
<?php
require_once 'config/database.php';
require_once 'config/constants.php';

echo "<h1>Configuration Test</h1>";
echo "<p><strong>App Name:</strong> " . APP_NAME . "</p>";
echo "<p><strong>Database:</strong> " . DB_NAME . "</p>";
echo "<p><strong>Upload Dir:</strong> " . UPLOAD_DIR . "</p>";
echo "<p><strong>Face Recognition API:</strong> " . FACE_RECOGNITION_API . "</p>";

if ($db->ping()) {
    echo "<p style='color: green;'><strong>✓ Database Connected</strong></p>";
} else {
    echo "<p style='color: red;'><strong>✗ Database Connection Failed</strong></p>";
}

?>
```

Access: http://localhost/Smart-Student-Management-System/backend/test_config.php

---

## Step 5: Python AI Module Setup

### 5.1 Install Python Dependencies
```bash
# Navigate to project
cd C:\xampp\htdocs\Smart-Student-Management-System\ai_module

# Create virtual environment (recommended)
python -m venv venv

# Activate virtual environment
# Windows:
venv\Scripts\activate
# macOS/Linux:
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt
```

### 5.2 Create requirements.txt
File: `ai_module/requirements.txt`

```
Flask==2.3.0
numpy==1.24.0
opencv-python==4.7.0.72
opencv-contrib-python==4.7.0.72
face-recognition==1.3.5
Pillow==9.5.0
requests==2.31.0
python-dotenv==1.0.0
Werkzeug==2.3.0
```

### 5.3 Create Flask Application
File: `ai_module/flask_api/app.py`

```python
from flask import Flask, request, jsonify
import sys
import os

app = Flask(__name__)

# Add project root to path
sys.path.insert(0, os.path.dirname(os.path.dirname(__file__)))

from face_recognition import face_detector, face_encoder, attendance_processor

@app.route('/health', methods=['GET'])
def health_check():
    return jsonify({'status': 'ok', 'message': 'Flask API Running'})

@app.route('/api/face/detect', methods=['POST'])
def detect_face():
    """Detect face in uploaded image"""
    if 'image' not in request.files:
        return jsonify({'error': 'No image provided'}), 400
    
    file = request.files['image']
    # Implementation pending
    return jsonify({'status': 'success'})

@app.route('/api/face/encode', methods=['POST'])
def encode_face():
    """Generate face encoding"""
    if 'image' not in request.files:
        return jsonify({'error': 'No image provided'}), 400
    
    file = request.files['image']
    # Implementation pending
    return jsonify({'status': 'success'})

@app.route('/api/attendance/process', methods=['POST'])
def process_attendance():
    """Process attendance from camera"""
    # Implementation pending
    return jsonify({'status': 'success'})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
```

### 5.4 Run Flask Server
```bash
# From ai_module directory
python flask_api/app.py

# Should display:
# WARNING in app.flaskenv - This is a development server. 
# Running on http://127.0.0.1:5000
```

---

## Step 6: Frontend Configuration

### 6.1 Create Frontend Index
File: `frontend/index.html`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Student Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">Smart Student Management</a>
        </div>
    </nav>
    
    <div class="container mt-5">
        <h1>Welcome</h1>
        <p>System is ready to use. Navigate to login page.</p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
```

### 6.2 Configure Main CSS
File: `frontend/css/style.css`

```css
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
}

.navbar {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Add more styles as needed */
```

---

## Step 7: Verification Checklist

### Database
- [ ] MySQL service running
- [ ] Database "smart_student_management" created
- [ ] 18 tables present
- [ ] Sample data inserted (optional)

### Backend
- [ ] PHP 8 configured
- [ ] database.php configuration correct
- [ ] constants.php configured
- [ ] Upload directories created with correct permissions
- [ ] test_config.php shows "Database Connected"

### AI Module
- [ ] Python 3.8+ installed
- [ ] Virtual environment created
- [ ] Dependencies installed
- [ ] Flask server running on port 5000
- [ ] Health check returns OK

### Frontend
- [ ] HTML files created
- [ ] CSS properly linked
- [ ] Bootstrap CDN accessible
- [ ] Can access http://localhost/Smart-Student-Management-System

---

## Step 8: Test the Setup

### 8.1 Test Database Connection
```
URL: http://localhost/Smart-Student-Management-System/backend/test_config.php
Expected: Green "Database Connected" message
```

### 8.2 Test Flask API
```
URL: http://localhost:5000/health
Expected: {"status": "ok", "message": "Flask API Running"}
```

### 8.3 Test Frontend
```
URL: http://localhost/Smart-Student-Management-System/frontend/index.html
Expected: Page loads with navbar and welcome message
```

---

## Troubleshooting

### Apache not starting
- Check if port 80 is in use
- Run XAMPP Control Panel as Administrator
- Check Apache error logs: xampp/apache/logs/error.log

### MySQL not starting
- Check if port 3306 is in use
- Check MySQL error logs: xampp/mysql/data/mysql_error.log
- Reset MySQL: xampp/mysql/bin/mysqld --defaults-file=xampp/mysql/bin/my.ini --reinstall

### Python connection issues
- Ensure XAMPP is running
- Check firewall settings
- Verify MySQL user credentials

### Face Recognition library issues
- Use 64-bit Python
- May require cmake and C++ build tools
- Alternatively, use Docker for Python

---

## Environment Configuration

### .env file (Optional)
Create: `backend/.env`

```
DB_HOST=localhost
DB_USER=app_user
DB_PASS=App@Secure123
DB_NAME=smart_student_management
JWT_SECRET=your_secret_key_change_in_production
FACE_RECOGNITION_THRESHOLD=0.6
```

### Load .env
```php
<?php
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        putenv($line);
    }
}
?>
```

---

## Next Steps

1. Create login page with authentication
2. Create role-based dashboards
3. Implement API endpoints
4. Create face recognition interface
5. Build reporting system
6. User acceptance testing

---

## Support & Documentation

- PHP Documentation: https://www.php.net/manual/
- MySQL Documentation: https://dev.mysql.com/doc/
- Bootstrap Documentation: https://getbootstrap.com/docs/
- Face Recognition Docs: https://github.com/ageitgey/face_recognition
- Flask Documentation: https://flask.palletsprojects.com/
