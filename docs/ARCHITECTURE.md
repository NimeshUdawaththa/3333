# System Architecture Documentation
## Web-Based Smart Student Management and Attendance System

---

## 1. High-Level Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                         CLIENT LAYER                            │
│                   (Frontend - Web Browser)                       │
│  ┌─────────────┐ ┌─────────────┐ ┌──────────────┐              │
│  │   Admin     │ │   Staff     │ │  Lecturer    │  │ Student │
│  │ Dashboard   │ │ Dashboard   │ │  Dashboard   │  │Dashboard│
│  └─────────────┘ └─────────────┘ └──────────────┘              │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                HTTP/REST API
                 (JSON Payload)
                           │
┌──────────────────────────▼──────────────────────────────────────┐
│                     APPLICATION LAYER                           │
│                    (Backend - PHP 8)                            │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  API Routes / Controllers                                │   │
│  │  - Authentication / Authorization                        │   │
│  │  - User Management                                       │   │
│  │  - Course Management                                     │   │
│  │  - Attendance Management                                 │   │
│  │  - Assignment Management                                 │   │
│  │  - Marks Management                                      │   │
│  │  - Announcements                                         │   │
│  │  - Reports & Analytics                                   │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Business Logic / Models                                 │   │
│  │  - User Model                                            │   │
│  │  - Student Model                                         │   │
│  │  - Course Model                                          │   │
│  │  - Attendance Model                                      │   │
│  │  - Mark Model                                            │   │
│  │  - Report Generator                                      │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────┬──────────────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
        ▼                  ▼                  ▼
┌─────────────────┐ ┌────────────────┐ ┌─────────────────┐
│  DATABASE       │ │  AI MODULE     │ │  FILE STORAGE   │
│   LAYER         │ │  (Python/Flask)│ │                 │
│  MySQL 8.0      │ │                │ │ - Documents     │
│                 │ │ - Face Detect  │ │ - Images        │
│                 │ │ - Encoder      │ │ - Submissions   │
│                 │ │ - API Routes   │ │ - Face Captures │
│                 │ │ - ML Models    │ │                 │
└─────────────────┘ └────────────────┘ └─────────────────┘
```

---

## 2. Technology Stack Details

### Frontend Layer
**Technologies**: HTML5, CSS3, Bootstrap 5, JavaScript  
**Components**:
- Responsive UI for mobile and desktop
- Role-based dashboards (Admin, Staff, Lecturer, Student)
- Real-time notifications using AJAX
- Face capture interface for attendance
- Form validation (client-side and server-side)
- Charts and graphs for reports (Chart.js/D3.js)

### Backend Layer
**Framework**: PHP 8 (Procedural/OOP)  
**Architecture Pattern**: MVC (Model-View-Controller)  
**Key Components**:
- RESTful API endpoints
- Authentication (JWT or Session-based)
- Authorization middleware (Role-based access control)
- Input validation and sanitization
- Error handling and logging
- CORS handling for API calls

### Database Layer
**DBMS**: MySQL 8.0  
**Features**:
- Normalized schema (3NF)
- Foreign key constraints
- Indexes for performance
- Stored procedures (optional)
- Views for reporting

### AI Module
**Framework**: Python 3.8+ with Flask  
**Libraries**:
- OpenCV (cv2) - Image processing
- face_recognition - Face detection and encoding
- NumPy - Numerical computations
- PIL/Pillow - Image manipulation

**API Endpoints**:
- `/api/face/detect` - Detect faces in image
- `/api/face/encode` - Generate face encoding
- `/api/face/compare` - Compare face with database
- `/api/attendance/process` - Process attendance from camera

---

## 3. System Modules

### Module 1: Authentication & Authorization
**Responsible For**:
- User login/logout
- Session management
- Password reset
- Role-based access control (RBAC)
- Permission checking

**Key Files**:
- backend/api/auth/login.php
- backend/api/auth/logout.php
- backend/api/auth/verify_token.php
- backend/middleware/AuthMiddleware.php

---

### Module 2: User Management
**Responsible For**:
- User CRUD operations
- Profile management
- User account activation/deactivation
- Permission management

**Supported Roles**:
1. **Admin**: System administration, user creation
2. **Academic Staff**: Staff coordination, support
3. **Lecturer**: Course management, marking, attendance
4. **Student**: View own data, assignments, marks

**Key Files**:
- backend/api/users/create.php
- backend/api/users/update.php
- backend/models/User.php

---

### Module 3: Student Management
**Responsible For**:
- Student registration
- Profile updates
- Enrollment tracking
- Face encoding storage

**Key Files**:
- backend/api/students/register.php
- backend/api/students/update.php
- backend/api/students/list.php
- backend/models/Student.php

---

### Module 4: Course Management
**Responsible For**:
- Create and manage courses
- Assign lecturers to courses
- Manage course schedules
- Enroll students in courses

**Key Files**:
- backend/api/courses/create.php
- backend/api/courses/enroll.php
- backend/api/courses/list.php
- backend/models/Course.php

---

### Module 5: Face Recognition Attendance
**Responsible For**:
- Capture student faces
- Generate face encodings
- Mark attendance using face recognition
- Confidence score calculation

**Process Flow**:
1. Camera captures face image
2. Python module detects face
3. Generates 128-D face encoding
4. Compares with database encodings
5. Returns student ID if match found
6. Records attendance

**Key Files**:
- ai_module/face_recognition/face_detector.py
- ai_module/face_recognition/face_encoder.py
- ai_module/face_recognition/attendance_processor.py
- ai_module/flask_api/app.py
- backend/api/attendance/mark.php

---

### Module 6: Assignment Management
**Responsible For**:
- Create assignments
- Manage deadlines
- Track submissions
- Store submission files

**Key Files**:
- backend/api/assignments/create.php
- backend/api/assignments/submit.php
- backend/api/assignments/list.php
- backend/models/Assignment.php

---

### Module 7: Marks Management
**Responsible For**:
- Record marks for different assessments
- Calculate grade points
- Generate transcripts
- Final result calculation

**Assessment Types**:
- Continuous Assessment (CA)
- Midterm Exam
- Final Exam
- Project Work
- Practical Work

**Key Files**:
- backend/api/marks/record.php
- backend/api/marks/calculate.php
- backend/api/marks/transcript.php
- backend/models/Mark.php

---

### Module 8: Reports & Analytics
**Responsible For**:
- Generate attendance reports
- Generate academic reports
- Dashboard analytics
- Statistical summaries

**Key Reports**:
- Student Attendance Report (per course)
- Course Attendance Report (all students)
- Academic Performance Report
- Grade Distribution Report
- Enrollment Statistics

**Key Files**:
- backend/api/reports/attendance.php
- backend/api/reports/marks.php
- backend/api/reports/analytics.php

---

### Module 9: Announcements
**Responsible For**:
- Broadcast system messages
- Target-specific groups
- Set expiry dates
- Priority levels

**Announcement Types**:
- General (all users)
- Department-specific
- Course-specific
- Student-specific

**Key Files**:
- backend/api/announcements/create.php
- backend/api/announcements/list.php

---

## 4. Data Flow Diagrams

### Attendance Marking Flow
```
1. Student enters classroom
    │
    ▼
2. Face recognition module activates (camera)
    │
    ▼
3. Face image captured
    │
    ▼
4. Python module: face_detector.detect_faces()
    │
    ├─ No face detected → Retry capture
    │
    ▼ (Face detected)
5. Python module: face_encoder.encode_face()
    │
    ├─ Generates 128-D encoding
    │
    ▼
6. Python module: attendance_processor.find_match()
    │
    ├─ Compare with stored encodings
    ├─ Calculate confidence scores
    ├─ Find best match
    │
    ▼
7. Return student_id + confidence
    │
    ├─ High confidence (>0.6) → Record as Present
    ├─ Medium confidence → Manual verification
    ├─ Low confidence → Retry
    │
    ▼
8. Backend: Record attendance in DB
    │
    ├─ INSERT into attendance table
    ├─ Store face_recognition_confidence
    ├─ Store face_image_path
    │
    ▼
9. Send SMS/Email notification
    │
    ▼
10. Update attendance_reports
```

### Assignment Submission Flow
```
1. Lecturer creates assignment
    │
    ├─ title, description, due_date, total_marks
    │
    ▼
2. Assignment published to course
    │
    ▼
3. Students view assignment in dashboard
    │
    ▼
4. Student uploads submission file
    │
    ├─ File validation (size, type)
    ├─ File stored on server
    │
    ▼
5. Record submission in DB
    │
    ├─ submission_date, submission_file_path
    ├─ status = 'submitted' or 'late'
    │
    ▼
6. Lecturer receives notification
    │
    ▼
7. Lecturer evaluates and records marks
    │
    ├─ obtained_marks, feedback
    │
    ▼
8. Student receives marks notification
```

### Marks & Results Flow
```
1. Lecturer records marks for each assessment
    │
    ├─ continuous, midterm, final, project, practical
    │
    ▼
2. System calculates percentage for each assessment
    │
    ├─ percentage = (obtained_marks / total_marks) * 100
    │
    ▼
3. System applies weightage to assessments
    │
    ├─ final_marks = Σ (assessment_marks * weightage)
    │
    ▼
4. System converts marks to grade
    │
    ├─ 90-100 = A, 80-89 = B+, 70-79 = B, etc.
    │
    ▼
5. Record in final_results table
    │
    ├─ final_marks, final_percentage, final_grade, status
    │
    ▼
6. Generate transcript/report
    │
    ▼
7. Notify student of results
```

---

## 5. API Endpoints Structure

### Authentication Endpoints
```
POST   /api/auth/login          - User login
POST   /api/auth/logout         - User logout
POST   /api/auth/refresh-token  - Refresh JWT token
POST   /api/auth/reset-password - Reset password
```

### User Management
```
GET    /api/users/profile       - Get user profile
PUT    /api/users/profile       - Update profile
GET    /api/users/list          - List users (admin)
POST   /api/users/create        - Create user (admin)
DELETE /api/users/{id}          - Delete user (admin)
```

### Course Management
```
GET    /api/courses/list        - List all courses
GET    /api/courses/{id}        - Get course details
POST   /api/courses/create      - Create course (admin)
PUT    /api/courses/{id}        - Update course
POST   /api/courses/enroll      - Enroll student
GET    /api/courses/{id}/students - Get enrolled students
```

### Attendance
```
POST   /api/attendance/mark     - Mark attendance
GET    /api/attendance/student/{id} - Get student attendance
GET    /api/attendance/course/{id}  - Get course attendance
GET    /api/attendance/report   - Generate report
```

### Assignments
```
POST   /api/assignments/create  - Create assignment
GET    /api/assignments/list    - List assignments
POST   /api/assignments/submit  - Submit assignment
GET    /api/assignments/submissions - List submissions
```

### Marks
```
POST   /api/marks/record        - Record marks
GET    /api/marks/transcript    - Get transcript
PUT    /api/marks/{id}          - Update marks
GET    /api/marks/course/{id}   - Get course marks
```

### Announcements
```
POST   /api/announcements/create - Create announcement
GET    /api/announcements/list   - List announcements
PUT    /api/announcements/{id}   - Update announcement
DELETE /api/announcements/{id}   - Delete announcement
```

---

## 6. Security Measures

### Authentication
- Passwords hashed using bcrypt or Argon2
- JWT or session-based authentication
- HTTPS/TLS encryption
- Token expiration (15 min for JWT, 30 min for session)

### Authorization
- Role-based access control (RBAC)
- Permission verification at API level
- Middleware checks on protected routes

### Data Protection
- Input validation and sanitization
- SQL injection prevention (prepared statements)
- XSS protection (output encoding)
- CSRF protection (tokens)
- Rate limiting on API endpoints
- Audit logging of all changes

### Face Recognition
- Face encodings never exposed
- Confidence thresholds enforced
- Manual verification for low confidence
- Image retention policy

---

## 7. Deployment Structure

### Development Environment
```
XAMPP Installation:
- Apache (HTTP Server)
- MySQL (Database)
- PHP 8 (Backend)
- Python 3 (AI Module)

Directory:
htdocs/Smart-Student-Management-System/
├── frontend/
├── backend/
├── ai_module/
└── database/
```

### Running the System

**1. Database Setup**:
```bash
# Import schema
mysql -u root -p < database/database.sql
```

**2. Backend (PHP)**:
```bash
# Configure PHP settings in backend/config/database.php
# Access via: http://localhost/Smart-Student-Management-System
```

**3. AI Module (Python)**:
```bash
# Install dependencies
pip install -r ai_module/requirements.txt

# Run Flask server
python ai_module/flask_api/app.py
# Runs on: http://localhost:5000
```

**4. Frontend**:
```bash
# Open in browser
http://localhost/Smart-Student-Management-System/frontend/index.html
```

---

## 8. Performance Optimization

### Database
- Query optimization and indexing
- Connection pooling
- Caching frequently accessed data
- Pagination for large datasets

### Backend
- API response caching
- Lazy loading for related data
- Batch operations for bulk updates
- Asynchronous processing for heavy tasks

### Frontend
- Lazy loading of images
- Minification of CSS/JS
- Browser caching
- CDN for static assets

### Face Recognition
- GPU acceleration for model inference
- Face encoding caching
- Batch processing for multiple students

---

## 9. Scalability Considerations

- Horizontal scaling: Multiple web servers
- Load balancing: Nginx/HAProxy
- Database replication: MySQL Master-Slave
- Redis caching layer
- Microservices for AI module
- Message queue for background tasks (RabbitMQ, Redis)

---

## 10. Maintenance & Monitoring

- Error logging and monitoring
- Performance metrics tracking
- Database backup strategy (daily)
- Log rotation and cleanup
- Regular security updates
- Version control (Git)
- CI/CD pipeline (optional)

