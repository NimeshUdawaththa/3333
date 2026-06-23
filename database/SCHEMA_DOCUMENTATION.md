# Database Schema Documentation
## Smart Student Management and Attendance System

---

## Table: USERS
**Purpose**: Base table for all users in the system

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| user_id | INT | PK, AUTO_INCREMENT | Unique user identifier |
| username | VARCHAR(50) | UNIQUE, NOT NULL | Login username |
| email | VARCHAR(100) | UNIQUE, NOT NULL | Email address |
| password_hash | VARCHAR(255) | NOT NULL | Hashed password (SHA-256 or bcrypt) |
| full_name | VARCHAR(100) | NOT NULL | Full name of user |
| phone | VARCHAR(20) | - | Contact phone number |
| user_role | ENUM | NOT NULL | Values: admin, academic_staff, lecturer, student |
| profile_picture | VARCHAR(255) | - | Path to profile image |
| is_active | BOOLEAN | DEFAULT TRUE | Account status |
| created_at | TIMESTAMP | DEFAULT NOW() | Account creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: user_role, is_active  
**Relationships**: Base for STUDENTS, LECTURERS, ACADEMIC_STAFF

---

## Table: DEPARTMENTS
**Purpose**: Manage academic departments

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| dept_id | INT | PK, AUTO_INCREMENT | Unique department identifier |
| dept_name | VARCHAR(100) | UNIQUE, NOT NULL | Department name (e.g., Computer Science) |
| dept_code | VARCHAR(20) | UNIQUE, NOT NULL | Department code (e.g., CS, ENG) |
| head_id | INT | FK→users(user_id) | Department head (admin user) |
| description | TEXT | - | Department description |
| is_active | BOOLEAN | DEFAULT TRUE | Department status |
| created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: dept_code, is_active  
**Relationships**: 1:Many with STUDENTS, LECTURERS, COURSES

---

## Table: STUDENTS
**Purpose**: Store student-specific information

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| student_id | INT | PK, AUTO_INCREMENT | Unique student identifier |
| user_id | INT | FK→users(user_id), UNIQUE | Reference to USERS table (1:1) |
| registration_number | VARCHAR(50) | UNIQUE, NOT NULL | Student registration/roll number |
| enrollment_year | INT | NOT NULL | Year of enrollment (e.g., 2024) |
| batch | VARCHAR(10) | - | Batch identifier (e.g., Batch A, 2024) |
| dept_id | INT | FK→departments(dept_id), NOT NULL | Department ID |
| date_of_birth | DATE | - | Student's DOB |
| address | TEXT | - | Residential address |
| guardian_name | VARCHAR(100) | - | Parent/Guardian name |
| guardian_phone | VARCHAR(20) | - | Parent/Guardian phone |
| face_encoding | LONGBLOB | - | Encoded face recognition data (binary) |
| face_encoding_updated_at | TIMESTAMP | - | Last face encoding update |
| is_active | BOOLEAN | DEFAULT TRUE | Student status (active/inactive) |
| created_at | TIMESTAMP | DEFAULT NOW() | Record creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: registration_number, enrollment_year, dept_id, is_active  
**Relationships**: 1:1 with USERS, 1:Many with COURSE_ENROLLMENT, ATTENDANCE, MARKS

**Important Notes**:
- face_encoding: Used for face recognition comparison (stored as binary blob)
- registration_number: Unique student ID per institution

---

## Table: LECTURERS
**Purpose**: Store lecturer-specific information

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| lecturer_id | INT | PK, AUTO_INCREMENT | Unique lecturer identifier |
| user_id | INT | FK→users(user_id), UNIQUE | Reference to USERS table (1:1) |
| lecturer_code | VARCHAR(50) | UNIQUE, NOT NULL | Lecturer identification code |
| dept_id | INT | FK→departments(dept_id), NOT NULL | Department ID |
| specialization | VARCHAR(100) | - | Subject specialization |
| qualification | VARCHAR(255) | - | Academic qualifications (comma-separated) |
| office_location | VARCHAR(100) | - | Office room number/location |
| is_active | BOOLEAN | DEFAULT TRUE | Lecturer status |
| created_at | TIMESTAMP | DEFAULT NOW() | Record creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: lecturer_code, dept_id, is_active  
**Relationships**: 1:1 with USERS, 1:Many with COURSES, ASSIGNMENTS, MARKS

---

## Table: ACADEMIC_STAFF
**Purpose**: Store academic staff information (coordinators, advisors)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| staff_id | INT | PK, AUTO_INCREMENT | Unique staff identifier |
| user_id | INT | FK→users(user_id), UNIQUE | Reference to USERS table (1:1) |
| staff_code | VARCHAR(50) | UNIQUE, NOT NULL | Staff identification code |
| dept_id | INT | FK→departments(dept_id), NOT NULL | Department ID |
| designation | VARCHAR(100) | - | Job designation (e.g., HOD, Coordinator) |
| specialization | VARCHAR(100) | - | Area of specialization |
| office_location | VARCHAR(100) | - | Office location |
| is_active | BOOLEAN | DEFAULT TRUE | Staff status |
| created_at | TIMESTAMP | DEFAULT NOW() | Record creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: staff_code, dept_id, is_active  
**Relationships**: 1:1 with USERS

---

## Table: COURSES
**Purpose**: Manage course information

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| course_id | INT | PK, AUTO_INCREMENT | Unique course identifier |
| course_code | VARCHAR(20) | UNIQUE, NOT NULL | Course code (e.g., CS101, MATH201) |
| course_name | VARCHAR(150) | NOT NULL | Full course name |
| course_description | TEXT | - | Detailed course description |
| credits | INT | NOT NULL | Credit hours/points |
| dept_id | INT | FK→departments(dept_id), NOT NULL | Offering department |
| semester | INT | - | Semester (1-8) |
| academic_year | VARCHAR(10) | - | Academic year (e.g., 2023-24) |
| lecturer_id | INT | FK→lecturers(lecturer_id) | Assigned lecturer |
| max_enrollment | INT | DEFAULT 50 | Maximum student enrollment |
| is_active | BOOLEAN | DEFAULT TRUE | Course status |
| created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: course_code, dept_id, lecturer_id, academic_year, is_active  
**Relationships**: 1:Many with COURSE_ENROLLMENT, ASSIGNMENTS, ATTENDANCE, MARKS

---

## Table: COURSE_ENROLLMENT
**Purpose**: Many-to-Many relationship between STUDENTS and COURSES

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| enrollment_id | INT | PK, AUTO_INCREMENT | Unique enrollment record ID |
| course_id | INT | FK→courses(course_id), NOT NULL | Course being taken |
| student_id | INT | FK→students(student_id), NOT NULL | Student enrolling |
| enrollment_date | DATE | NOT NULL | Date of enrollment |
| status | ENUM | DEFAULT 'active' | Values: active, dropped, completed, failed |
| created_at | TIMESTAMP | DEFAULT NOW() | Record creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: course_id, student_id, status  
**Unique Constraint**: (course_id, student_id) - prevent duplicate enrollment  
**Purpose**: Track which students are in which courses

---

## Table: LECTURE_SCHEDULES
**Purpose**: Define recurring lecture schedule

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| schedule_id | INT | PK, AUTO_INCREMENT | Unique schedule record ID |
| course_id | INT | FK→courses(course_id), NOT NULL | Course reference |
| day_of_week | ENUM | NOT NULL | Values: Monday-Saturday |
| start_time | TIME | NOT NULL | Lecture start time (HH:MM:SS) |
| end_time | TIME | NOT NULL | Lecture end time (HH:MM:SS) |
| classroom_location | VARCHAR(100) | NOT NULL | Room/building location |
| is_active | BOOLEAN | DEFAULT TRUE | Schedule status |
| created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: course_id, day_of_week  
**Purpose**: Define when and where each course meets

---

## Table: ATTENDANCE
**Purpose**: Face recognition-based attendance tracking

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| attendance_id | INT | PK, AUTO_INCREMENT | Unique attendance record ID |
| course_id | INT | FK→courses(course_id), NOT NULL | Course reference |
| student_id | INT | FK→students(student_id), NOT NULL | Student reference |
| attendance_date | DATE | NOT NULL | Date of attendance |
| check_in_time | TIME | - | Time student checked in |
| check_out_time | TIME | - | Time student checked out |
| attendance_status | ENUM | DEFAULT 'absent' | Values: present, absent, late, excused |
| face_recognition_confidence | FLOAT | - | Confidence score (0-1.0) from face recognition |
| face_image_path | VARCHAR(255) | - | Path to captured face image |
| remarks | TEXT | - | Additional notes (late reason, etc.) |
| recorded_by | INT | FK→lecturers(lecturer_id) | Lecturer who recorded |
| created_at | TIMESTAMP | DEFAULT NOW() | Record creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: course_id, student_id, attendance_date, attendance_status  
**Unique Constraint**: (course_id, student_id, attendance_date)  
**Special Features**: 
- face_recognition_confidence: ML model confidence score
- face_image_path: Stores captured image for verification

---

## Table: ASSIGNMENTS
**Purpose**: Manage course assignments

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| assignment_id | INT | PK, AUTO_INCREMENT | Unique assignment ID |
| course_id | INT | FK→courses(course_id), NOT NULL | Course reference |
| assignment_title | VARCHAR(150) | NOT NULL | Assignment title |
| assignment_description | TEXT | - | Detailed description |
| total_marks | INT | NOT NULL | Maximum marks for assignment |
| due_date | DATETIME | NOT NULL | Submission deadline |
| created_by | INT | FK→lecturers(lecturer_id), NOT NULL | Lecturer creating assignment |
| attachment_path | VARCHAR(255) | - | Path to assignment document/file |
| is_active | BOOLEAN | DEFAULT TRUE | Assignment status |
| created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: course_id, due_date, is_active  
**Relationships**: 1:Many with ASSIGNMENT_SUBMISSIONS

---

## Table: ASSIGNMENT_SUBMISSIONS
**Purpose**: Track student assignment submissions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| submission_id | INT | PK, AUTO_INCREMENT | Unique submission record ID |
| assignment_id | INT | FK→assignments(assignment_id), NOT NULL | Assignment reference |
| student_id | INT | FK→students(student_id), NOT NULL | Student who submitted |
| submission_date | DATETIME | NOT NULL | When student submitted |
| submission_file_path | VARCHAR(255) | NOT NULL | Path to submitted file |
| submission_text | TEXT | - | Inline text submission (optional) |
| submission_status | ENUM | DEFAULT 'submitted' | Values: submitted, late, not_submitted |
| submitted_at | TIMESTAMP | DEFAULT NOW() | Server timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: assignment_id, student_id, submission_status  
**Unique Constraint**: (assignment_id, student_id)  
**Purpose**: Track which students submitted what and when

---

## Table: MARKS
**Purpose**: Individual assessment marks for students

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| mark_id | INT | PK, AUTO_INCREMENT | Unique mark record ID |
| course_id | INT | FK→courses(course_id), NOT NULL | Course reference |
| student_id | INT | FK→students(student_id), NOT NULL | Student reference |
| assessment_type | ENUM | NOT NULL | Values: continuous, midterm, final, project, practical |
| obtained_marks | DECIMAL(5,2) | - | Marks obtained by student |
| total_marks | INT | NOT NULL | Maximum marks for this assessment |
| percentage | DECIMAL(5,2) | - | Percentage obtained |
| grade | CHAR(2) | - | Letter grade (A, B+, B, etc.) |
| weightage | INT | - | Weight percentage for final calculation |
| remarks | TEXT | - | Feedback/comments |
| marked_by | INT | FK→lecturers(lecturer_id) | Lecturer who marked |
| marked_date | DATE | - | Date of marking |
| created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: course_id, student_id, assessment_type, percentage  
**Unique Constraint**: (course_id, student_id, assessment_type)  
**Purpose**: Store individual assessment scores

---

## Table: FINAL_RESULTS
**Purpose**: Aggregated final grades per course per student

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| result_id | INT | PK, AUTO_INCREMENT | Unique result record ID |
| course_id | INT | FK→courses(course_id), NOT NULL | Course reference |
| student_id | INT | FK→students(student_id), NOT NULL | Student reference |
| final_marks | DECIMAL(5,2) | - | Calculated final marks |
| final_percentage | DECIMAL(5,2) | - | Calculated percentage |
| final_grade | CHAR(2) | - | Final letter grade |
| status | ENUM | DEFAULT 'pending' | Values: passed, failed, pending |
| result_date | DATE | - | Date result finalized |
| created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: course_id, student_id, status  
**Unique Constraint**: (course_id, student_id)  
**Purpose**: Store final calculated grades (derived from MARKS table)

---

## Table: ANNOUNCEMENTS
**Purpose**: System-wide announcements with targeting

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| announcement_id | INT | PK, AUTO_INCREMENT | Unique announcement ID |
| title | VARCHAR(200) | NOT NULL | Announcement title |
| content | TEXT | NOT NULL | Announcement message |
| announcement_type | ENUM | DEFAULT 'general' | Values: general, department, course, student |
| target_dept_id | INT | FK→departments(dept_id) | Department if type='department' |
| target_course_id | INT | FK→courses(course_id) | Course if type='course' |
| target_student_id | INT | FK→students(student_id) | Student if type='student' |
| created_by | INT | FK→users(user_id), NOT NULL | Who created announcement |
| priority | ENUM | DEFAULT 'medium' | Values: low, medium, high |
| expiry_date | DATETIME | - | When announcement expires |
| is_active | BOOLEAN | DEFAULT TRUE | Announcement status |
| created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: announcement_type, priority, is_active, created_at  
**Purpose**: Broadcast messages to different user groups

---

## Table: ATTENDANCE_REPORTS
**Purpose**: Aggregated attendance statistics for analytics

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| report_id | INT | PK, AUTO_INCREMENT | Unique report record ID |
| course_id | INT | FK→courses(course_id), NOT NULL | Course reference |
| student_id | INT | FK→students(student_id), NOT NULL | Student reference |
| total_classes | INT | DEFAULT 0 | Total classes held |
| classes_attended | INT | DEFAULT 0 | Classes student attended |
| classes_absent | INT | DEFAULT 0 | Absences |
| classes_late | INT | DEFAULT 0 | Late arrivals |
| classes_excused | INT | DEFAULT 0 | Excused absences |
| attendance_percentage | DECIMAL(5,2) | - | Calculated percentage |
| is_compliant | BOOLEAN | DEFAULT TRUE | Meets minimum attendance (>75%) |
| report_date | DATE | - | Date of report generation |
| created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp |
| updated_at | TIMESTAMP | ON UPDATE NOW() | Last update timestamp |

**Indexes**: course_id, student_id, attendance_percentage  
**Unique Constraint**: (course_id, student_id, report_date)  
**Purpose**: Summary statistics for reporting

---

## Table: AUDIT_LOGS
**Purpose**: Track system changes for security and compliance

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| log_id | INT | PK, AUTO_INCREMENT | Unique log record ID |
| user_id | INT | FK→users(user_id) | User who made change |
| action_type | VARCHAR(100) | NOT NULL | Type of action (INSERT, UPDATE, DELETE) |
| table_name | VARCHAR(100) | - | Table affected |
| record_id | INT | - | Record ID affected |
| old_values | JSON | - | Previous values (JSON format) |
| new_values | JSON | - | New values (JSON format) |
| ip_address | VARCHAR(45) | - | IP address of user |
| user_agent | TEXT | - | Browser/client information |
| created_at | TIMESTAMP | DEFAULT NOW() | When change occurred |

**Indexes**: user_id, action_type, created_at  
**Purpose**: Audit trail for compliance and debugging

---

## Table: SESSIONS
**Purpose**: Manage user login sessions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| session_id | VARCHAR(128) | PK | Unique session ID |
| user_id | INT | FK→users(user_id), NOT NULL | Session user |
| session_token | VARCHAR(255) | UNIQUE | Authentication token |
| ip_address | VARCHAR(45) | - | IP address of session |
| user_agent | TEXT | - | Browser information |
| login_time | TIMESTAMP | DEFAULT NOW() | Login timestamp |
| last_activity | TIMESTAMP | ON UPDATE NOW() | Last activity timestamp |
| logout_time | TIMESTAMP | - | Logout timestamp (if logged out) |
| is_active | BOOLEAN | DEFAULT TRUE | Session status |

**Indexes**: user_id, is_active  
**Purpose**: Track active user sessions

---

## Table: PASSWORD_RESET_TOKENS
**Purpose**: Handle password reset functionality

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| token_id | INT | PK, AUTO_INCREMENT | Unique token ID |
| user_id | INT | FK→users(user_id), NOT NULL | User requesting reset |
| reset_token | VARCHAR(255) | UNIQUE, NOT NULL | Random reset token |
| token_expiry | DATETIME | NOT NULL | When token expires (usually 1 hour) |
| is_used | BOOLEAN | DEFAULT FALSE | Whether token was used |
| created_at | TIMESTAMP | DEFAULT NOW() | Token creation timestamp |

**Indexes**: reset_token, is_used  
**Purpose**: Secure password reset mechanism

---

## Data Type Reference

| Type | Purpose | Example |
|------|---------|---------|
| INT | Integer IDs, counters | ID, marks, credit |
| VARCHAR(n) | Short text, codes | Names, emails, codes |
| TEXT | Long text | Descriptions, remarks |
| DATE | Date only | Birth dates, enrollment dates |
| TIME | Time only | Check-in time |
| DATETIME | Date + Time | Deadlines, submissions |
| TIMESTAMP | Auto date+time | Created_at, updated_at |
| DECIMAL(5,2) | Decimal numbers | Marks (99.99), percentage |
| BOOLEAN | True/False | is_active, is_compliant |
| ENUM | Predefined values | Status, role |
| LONGBLOB | Binary large object | Face encodings |
| JSON | JSON data | Audit log values |

---

## Naming Conventions

1. **Table Names**: UPPER_CASE (e.g., STUDENTS, COURSES)
2. **Column Names**: snake_case (e.g., user_id, first_name)
3. **Primary Key**: {table}_id (e.g., student_id)
4. **Foreign Key**: {referenced_table}_id (e.g., course_id)
5. **Boolean Columns**: is_{adjective} (e.g., is_active, is_compliant)
6. **Timestamp Columns**: created_at, updated_at

---

## Performance Considerations

1. **Indexes**: Applied to frequently queried columns
2. **Foreign Keys**: Ensure referential integrity
3. **Unique Constraints**: Prevent duplicates
4. **Timestamps**: Auto-populated for audit trail
5. **JSON Fields**: For flexible audit logging
6. **BLOB Fields**: For face encoding binary data

