# Entity-Relationship (ER) Diagram
## Smart Student Management and Attendance System

### Database Overview
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        SMART STUDENT MANAGEMENT SYSTEM                      │
│                           DATABASE RELATIONSHIPS                            │
└─────────────────────────────────────────────────────────────────────────────┘
```

## ER Diagram (Text Representation)

```
                        ┌──────────────┐
                        │    USERS     │
                        │(Base Entity) │
                        └──────┬───────┘
                               │ user_id (PK)
                ┌──────────────┼──────────────┐
                │              │              │
                ▼              ▼              ▼
        ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
        │  STUDENTS    │ │   LECTURERS  │ │ ACAD_STAFF   │
        │(1:1 to USERS)│ │(1:1 to USERS)│ │(1:1 to USERS)│
        └──────┬───────┘ └──────┬───────┘ └──────────────┘
               │ student_id     │ lecturer_id
               │                │
               ├─ dept_id ──────┤ dept_id
               │                │
        ┌──────▼────────┐       │      ┌────────────────────┐
        │  DEPARTMENTS  │◄──────┴──────┤ COURSES            │
        │ (Head = Admin)│              │ (Has Lecturer)     │
        └──────────────┘               └────────┬───────────┘
                                                │ course_id
                        ┌───────────────────────┼───────────────────┐
                        │                       │                   │
                        ▼                       ▼                   ▼
        ┌─────────────────────────┐  ┌──────────────────┐  ┌────────────────┐
        │ COURSE_ENROLLMENT       │  │ LECTURE_SCHEDULE │  │  ASSIGNMENTS   │
        │(Many-Many relationship) │  │  (1:Many)        │  │   (1:Many)     │
        │ course_id + student_id  │  └──────┬───────────┘  └────────┬───────┘
        └───────────┬─────────────┘         │                       │
                    │                       │                       ▼
                    │                  ┌────▼─────────────┐  ┌───────────────────┐
                    │                  │  ATTENDANCE      │  │ASSIGNMENT_SUBMISS │
                    │                  │(1:Many)          │  │  (1:Many)         │
                    │                  │Face Recognition  │  └────────┬──────────┘
                    │                  │Tracking          │           │
                    │                  └──────────────────┘           │
                    │                                                  │
                    └──────────────┬───────────────────────────────────┘
                                   │
                                   ▼
                        ┌─────────────────────┐
                        │      MARKS          │
                        │ (Continuous, Final) │
                        └────────────────────┘
                                   │
                                   ▼
                        ┌─────────────────────┐
                        │   FINAL_RESULTS     │
                        │ (Aggregated marks)  │
                        └────────────────────┘


┌──────────────────────┐
│   ANNOUNCEMENTS      │
│(General, Dept, Cour) │
│(Target-specific)     │
└──────────────────────┘

┌──────────────────────┐      ┌──────────────────────┐
│ ATTENDANCE_REPORTS   │      │    AUDIT_LOGS        │
│(Analytics/Summary)   │      │  (System Tracking)   │
└──────────────────────┘      └──────────────────────┘

┌──────────────────────┐      ┌──────────────────────┐
│     SESSIONS         │      │ PASSWORD_RESET_TOKEN │
│  (User Sessions)     │      │   (Auth Recovery)    │
└──────────────────────┘      └──────────────────────┘
```

## Entity Relationships Summary

### 1. USERS (Base Table)
- **1:1** with STUDENTS (user_role = 'student')
- **1:1** with LECTURERS (user_role = 'lecturer')
- **1:1** with ACADEMIC_STAFF (user_role = 'academic_staff')
- **1:Many** with ANNOUNCEMENTS (created_by)
- **1:Many** with SESSIONS (user_id)
- **1:Many** with AUDIT_LOGS (user_id)
- **1:Many** with PASSWORD_RESET_TOKENS (user_id)

### 2. DEPARTMENTS
- **1:Many** with STUDENTS (dept_id)
- **1:Many** with LECTURERS (dept_id)
- **1:Many** with ACADEMIC_STAFF (dept_id)
- **1:Many** with COURSES (dept_id)
- **1:1** with USERS (head_id - Department Head)

### 3. STUDENTS
- **1:1** with USERS (user_id - FK)
- **Many:Many** with COURSES (via COURSE_ENROLLMENT)
- **1:Many** with ATTENDANCE (student_id)
- **1:Many** with MARKS (student_id)
- **1:Many** with ASSIGNMENT_SUBMISSIONS (student_id)
- **1:Many** with FINAL_RESULTS (student_id)
- **1:Many** with ANNOUNCEMENTS (target_student_id - optional)

### 4. LECTURERS
- **1:1** with USERS (user_id - FK)
- **1:Many** with COURSES (lecturer_id)
- **1:Many** with ASSIGNMENTS (created_by)
- **1:Many** with MARKS (marked_by)
- **1:Many** with ATTENDANCE (recorded_by)

### 5. COURSES
- **Many:1** with DEPARTMENTS (dept_id)
- **Many:1** with LECTURERS (lecturer_id)
- **Many:Many** with STUDENTS (via COURSE_ENROLLMENT)
- **1:Many** with LECTURE_SCHEDULES (course_id)
- **1:Many** with ASSIGNMENTS (course_id)
- **1:Many** with ATTENDANCE (course_id)
- **1:Many** with MARKS (course_id)
- **1:Many** with FINAL_RESULTS (course_id)
- **1:Many** with ANNOUNCEMENTS (target_course_id - optional)

### 6. COURSE_ENROLLMENT
- **Many:1** with COURSES (course_id)
- **Many:1** with STUDENTS (student_id)
- **Purpose**: Track which students are enrolled in which courses

### 7. LECTURE_SCHEDULES
- **Many:1** with COURSES (course_id)
- **Purpose**: Define recurring lecture times and locations

### 8. ATTENDANCE
- **Many:1** with COURSES (course_id)
- **Many:1** with STUDENTS (student_id)
- **Many:1** with LECTURERS (recorded_by - who recorded attendance)
- **Unique Constraint**: (course_id, student_id, attendance_date)
- **Special Fields**: face_recognition_confidence, face_image_path

### 9. ASSIGNMENTS
- **Many:1** with COURSES (course_id)
- **Many:1** with LECTURERS (created_by)
- **1:Many** with ASSIGNMENT_SUBMISSIONS (assignment_id)

### 10. ASSIGNMENT_SUBMISSIONS
- **Many:1** with ASSIGNMENTS (assignment_id)
- **Many:1** with STUDENTS (student_id)
- **Unique Constraint**: (assignment_id, student_id)

### 11. MARKS
- **Many:1** with COURSES (course_id)
- **Many:1** with STUDENTS (student_id)
- **Many:1** with LECTURERS (marked_by)
- **Unique Constraint**: (course_id, student_id, assessment_type)
- **Assessment Types**: continuous, midterm, final, project, practical

### 12. FINAL_RESULTS
- **Many:1** with COURSES (course_id)
- **Many:1** with STUDENTS (student_id)
- **Unique Constraint**: (course_id, student_id)
- **Purpose**: Aggregated final grades from MARKS table

### 13. ANNOUNCEMENTS
- **Many:1** with USERS (created_by)
- **Many:1** with DEPARTMENTS (target_dept_id - optional)
- **Many:1** with COURSES (target_course_id - optional)
- **Many:1** with STUDENTS (target_student_id - optional)
- **Types**: General, Department-specific, Course-specific, Student-specific

### 14. ATTENDANCE_REPORTS
- **Many:1** with COURSES (course_id)
- **Many:1** with STUDENTS (student_id)
- **Purpose**: Summary statistics for attendance analytics

### 15. AUDIT_LOGS
- **Many:1** with USERS (user_id - who made the change)
- **Purpose**: Track all system changes for audit trail

### 16. SESSIONS
- **Many:1** with USERS (user_id)
- **Purpose**: Manage active user sessions

### 17. PASSWORD_RESET_TOKENS
- **Many:1** with USERS (user_id)
- **Purpose**: Handle password reset functionality

## Cardinality Notation
- **1:1** = One to One
- **1:Many** = One to Many
- **Many:Many** = Many to Many (via junction table)

## Key Constraints
- **Primary Keys (PK)**: Auto-increment integer IDs for all main entities
- **Foreign Keys (FK)**: Reference integrity maintained across tables
- **Unique Constraints**: Prevent duplicate records (e.g., enrollment, marks)
- **Check Constraints**: Ensure valid ENUM values
- **Not Null**: Critical fields marked as NOT NULL

## Data Integrity Rules
1. Students can enroll in multiple courses
2. Each course can have multiple students
3. Lecturers assign to specific courses
4. Attendance tracked per student per course per date
5. Marks tracked per student per course per assessment type
6. Final results calculated from marks
7. Announcements can target specific groups or be general

## Performance Optimization
- Indexes on frequently queried columns (user_id, course_id, student_id)
- Indexes on date/status fields for filtering
- Foreign key indexes for join operations
- Composite indexes for unique constraints
