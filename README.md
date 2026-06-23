# Web-Based Smart Student Management and Attendance System Using Real-Time Face Recognition

## Project Overview
A comprehensive web-based system for managing student information, tracking attendance using face recognition technology, managing assignments, marks, and academic schedules.

## Technology Stack
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend**: PHP 8
- **Database**: MySQL 8.0
- **AI Module**: Python 3.8+, OpenCV, face_recognition, Flask API
- **Development Environment**: VS Code + XAMPP

## User Roles
1. **Admin** - System administration, user management
2. **Academic Staff** - Staff management, staff coordination
3. **Lecturer** - Manage courses, assignments, marks, attendance
4. **Student** - View attendance, assignments, marks, schedules

## Main Features
- ✓ Student Management
- ✓ Face Recognition Attendance System
- ✓ Assignment Management
- ✓ Marks/Grades Management
- ✓ Lecture Schedule Management
- ✓ Announcements System
- ✓ Attendance Reports & Analytics

## Project Structure
```
Smart-Student-Management-System/
├── frontend/                    # Frontend files
│   ├── index.html
│   ├── css/
│   │   ├── style.css
│   │   └── bootstrap.min.css
│   ├── js/
│   │   └── main.js
│   ├── pages/
│   │   ├── login.html
│   │   ├── admin/
│   │   ├── staff/
│   │   ├── lecturer/
│   │   └── student/
│   ├── assets/
│   │   └── images/
│   └── components/
│
├── backend/                     # Backend PHP files
│   ├── config/
│   │   ├── database.php
│   │   └── constants.php
│   ├── api/
│   │   ├── auth/
│   │   ├── students/
│   │   ├── lecturers/
│   │   ├── courses/
│   │   ├── attendance/
│   │   ├── assignments/
│   │   ├── marks/
│   │   ├── schedules/
│   │   └── announcements/
│   ├── models/
│   │   ├── User.php
│   │   ├── Student.php
│   │   ├── Lecturer.php
│   │   ├── Course.php
│   │   ├── Attendance.php
│   │   ├── Assignment.php
│   │   ├── Mark.php
│   │   ├── Schedule.php
│   │   └── Announcement.php
│   ├── controllers/
│   ├── middleware/
│   ├── utils/
│   └── index.php
│
├── ai_module/                   # Python AI Module
│   ├── face_recognition/
│   │   ├── face_detector.py
│   │   ├── face_encoder.py
│   │   └── attendance_processor.py
│   ├── flask_api/
│   │   ├── app.py
│   │   └── routes.py
│   ├── models/
│   │   └── face_encodings/
│   └── requirements.txt
│
├── database/                    # Database files
│   ├── database.sql
│   ├── ER_DIAGRAM.md
│   └── SCHEMA_DOCUMENTATION.md
│
├── docs/                        # Documentation
│   ├── ARCHITECTURE.md
│   ├── API_DOCUMENTATION.md
│   ├── DATABASE_DESIGN.md
│   └── USER_MANUAL.md
│
├── config/                      # Configuration files
│   ├── xampp_setup.md
│   └── environment.example
│
└── .gitignore
```

## Setup Instructions
1. Extract project to htdocs (XAMPP)
2. Import database.sql into MySQL
3. Configure backend/config/database.php
4. Install Python dependencies
5. Run Flask API server
6. Access via http://localhost/Smart-Student-Management-System

## Database Setup
- Run `/database/database.sql` to create all tables
- See `/database/ER_DIAGRAM.md` for entity relationships
- See `/database/SCHEMA_DOCUMENTATION.md` for table details
