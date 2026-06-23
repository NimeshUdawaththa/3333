# Authentication System - Test Checklist

## Pre-Test Verification

- [ ] Database `smart_student_management` created
- [ ] All 18 tables exist in database
- [ ] Demo data imported from `database/demo_data.sql`
- [ ] Apache is running
- [ ] MySQL is running
- [ ] Project folder in `C:\xampp\htdocs\Smart-Student-Management-System\`

---

## Unit Tests

### Database Connection Test
- [ ] Can connect to MySQL database
- [ ] Database name: `smart_student_management`
- [ ] All tables present

### Authentication Tests

#### 1. Login with Admin Account
- [ ] Username: `admin`
- [ ] Password: `admin123`
- [ ] **Expected**: Redirect to `admin_dashboard.php`
- [ ] **Verify**: Dashboard shows statistics
- [ ] **Verify**: Sidebar shows admin menu options

#### 2. Login with Lecturer Account
- [ ] Username: `lecturer`
- [ ] Password: `lecturer123`
- [ ] **Expected**: Redirect to `lecturer_dashboard.php`
- [ ] **Verify**: Shows "My Courses" section
- [ ] **Verify**: Sidebar shows lecturer-specific options

#### 3. Login with Student Account
- [ ] Username: `student`
- [ ] Password: `student123`
- [ ] **Expected**: Redirect to `student_dashboard.php`
- [ ] **Verify**: Shows enrolled courses
- [ ] **Verify**: Shows GPA and attendance rate

#### 4. Login with Academic Staff Account
- [ ] Username: `academic`
- [ ] Password: `academic123`
- [ ] **Expected**: Redirect to `academic_dashboard.php`
- [ ] **Verify**: Shows management options
- [ ] **Verify**: Sidebar shows academic-specific options

### Session Management Tests

#### 1. Session Persistence
- [ ] Login successfully
- [ ] Navigate between pages
- [ ] **Verify**: Still logged in
- [ ] Check: Session data preserved

#### 2. Session Timeout
- [ ] Login successfully
- [ ] Wait (optional: configure shorter timeout for testing)
- [ ] Refresh page after 30+ minutes
- [ ] **Expected**: Redirected to login page with timeout message

#### 3. Browser Back Button
- [ ] Login successfully
- [ ] Click browser back button from dashboard
- [ ] **Expected**: Should not show form data or cached login

### Password Management Tests

#### 1. Change Password
- [ ] Login with any account
- [ ] Go to Profile → Change Password
- [ ] Enter current password (e.g., `admin123`)
- [ ] Enter new password (e.g., `newpass123`)
- [ ] Confirm new password
- [ ] **Verify**: "Password changed successfully" message
- [ ] Logout

#### 2. Verify New Password Works
- [ ] Login with new password
- [ ] **Expected**: Successful login

#### 3. Verify Old Password Doesn't Work
- [ ] Attempt login with old password
- [ ] **Expected**: "Invalid username/email or password" error

#### 4. Password Change Validation
- [ ] Go to Change Password
- [ ] Try changing with wrong current password
- [ ] **Expected**: Error message
- [ ] Try new password too short (< 6 chars)
- [ ] **Expected**: Error message

### Role-Based Access Control Tests

#### 1. Attempt Unauthorized Access
- [ ] Login as `student`
- [ ] Try accessing `/admin/` pages directly
- [ ] **Expected**: 403 Forbidden error page

#### 2. Role Redirect
- [ ] Login as `lecturer`
- [ ] **Expected**: Auto-redirect to lecturer dashboard
- [ ] Login as `student`
- [ ] **Expected**: Auto-redirect to student dashboard

#### 3. Middleware Protection
- [ ] Logout
- [ ] Try accessing `/dashboard/admin_dashboard.php` directly
- [ ] **Expected**: Redirect to login page

### Logout Tests

#### 1. Logout Functionality
- [ ] Login successfully
- [ ] Click "Logout"
- [ ] **Expected**: Redirected to login page
- [ ] **Verify**: Session cleared

#### 2. Session Invalidation
- [ ] Logout
- [ ] Use browser back button
- [ ] **Expected**: Cannot access previous dashboard
- [ ] Clicking should redirect to login

#### 3. Re-login After Logout
- [ ] Logout
- [ ] Login again with same credentials
- [ ] **Expected**: Successful login

### Navigation Tests

#### 1. Navigation Bar
- [ ] Login successfully
- [ ] **Verify**: Navigation bar shows user name
- [ ] **Verify**: Shows current role badge
- [ ] **Verify**: Dropdown menu works

#### 2. Sidebar Navigation
- [ ] Check each role's sidebar
- [ ] **Admin**: 9 menu items visible
- [ ] **Lecturer**: 6 menu items visible
- [ ] **Student**: 6 menu items visible
- [ ] **Academic**: 6 menu items visible

#### 3. Dashboard Statistics
- [ ] Admin dashboard: Shows 4 stat cards
- [ ] Lecturer dashboard: Shows 4 stat cards
- [ ] Student dashboard: Shows 4 stat cards
- [ ] Academic dashboard: Shows 4 stat cards

### Error Handling Tests

#### 1. Invalid Login
- [ ] Enter invalid username
- [ ] **Expected**: "Invalid username/email or password"
- [ ] Enter correct username, wrong password
- [ ] **Expected**: "Invalid username/email or password"

#### 2. Missing Fields
- [ ] Leave username empty, submit
- [ ] **Expected**: Form validation error
- [ ] Leave password empty, submit
- [ ] **Expected**: Form validation error

#### 3. SQL Injection Prevention
- [ ] Try SQL injection in username field: `admin' OR '1'='1`
- [ ] **Expected**: Invalid credentials error (not bypassed)
- [ ] Try special characters in password
- [ ] **Expected**: Normal error handling

### Database Tests

#### 1. Audit Logging
- [ ] Login with `admin` account
- [ ] Go to Admin Dashboard
- [ ] Check "Recent Activities" table
- [ ] **Verify**: Your login appears in audit logs

#### 2. User Table
- [ ] Check users table has demo accounts
- [ ] Verify password hashes (bcrypt format)
- [ ] Check user roles are correct

#### 3. Student Enrollment
- [ ] Login as `student`
- [ ] Check enrolled courses display
- [ ] Verify: Courses table populated
- [ ] Verify: course_enrollment table has records

---

## Integration Tests

### User Journey - Admin
- [ ] Login → View Dashboard → Navigate Pages → Change Password → Logout ✓

### User Journey - Lecturer
- [ ] Login → View Courses → View Students → Change Password → Logout ✓

### User Journey - Student
- [ ] Login → View Courses → View Marks → View Attendance → Change Password → Logout ✓

### User Journey - Academic Staff
- [ ] Login → View Dashboard → Manage Enrollments → Change Password → Logout ✓

---

## Performance Tests

### Page Load Times
- [ ] Login page: < 2 seconds
- [ ] Dashboard pages: < 2 seconds
- [ ] Profile page: < 2 seconds
- [ ] Change password page: < 2 seconds

### Database Queries
- [ ] Dashboard queries execute within 500ms
- [ ] No N+1 query problems
- [ ] Prepared statements used for all queries

---

## Browser Compatibility

- [ ] Chrome/Edge
- [ ] Firefox
- [ ] Safari
- [ ] Mobile browsers

### Responsive Design
- [ ] Desktop (1920px): All elements visible
- [ ] Tablet (768px): Sidebar collapses to hamburger
- [ ] Mobile (375px): Responsive navigation works

---

## Security Tests

### 1. Session Security
- [ ] Session cookies are HTTPOnly
- [ ] SameSite attribute set to Strict
- [ ] Session timeout working correctly
- [ ] No session data in URL

### 2. Password Security
- [ ] Passwords hashed with bcrypt
- [ ] Password not visible in database
- [ ] Password not logged anywhere
- [ ] Minimum 6 characters required

### 3. SQL Injection
- [ ] Prepared statements used everywhere
- [ ] Special characters handled safely
- [ ] No raw SQL in queries

### 4. XSS Prevention
- [ ] User input escaped with htmlspecialchars()
- [ ] No JavaScript injection possible
- [ ] All output properly escaped

### 5. CSRF Protection
- [ ] Forms use POST method
- [ ] Session validation on logout
- [ ] No sensitive operations with GET

---

## Accessibility Tests

- [ ] All form labels properly associated
- [ ] ARIA labels where needed
- [ ] Keyboard navigation works
- [ ] Color contrast adequate
- [ ] Focus indicators visible

---

## Documentation Tests

- [ ] QUICK_START.md is accurate
- [ ] AUTHENTICATION_GUIDE.md is comprehensive
- [ ] API documentation matches code
- [ ] Code comments are clear

---

## Final Checklist

- [ ] All tests passed
- [ ] No errors in browser console
- [ ] No PHP warnings/errors
- [ ] All pages load correctly
- [ ] All links work
- [ ] All buttons functional
- [ ] Database intact
- [ ] Demo data present
- [ ] Ready for production use

---

## Sign-Off

**Tester Name**: ___________________

**Date**: ___________________

**Status**: [ ] PASS [ ] FAIL [ ] PENDING

**Notes**:
```
_____________________________________________________________________

_____________________________________________________________________

_____________________________________________________________________
```

---

**Authentication System v1.0** - Test Report
