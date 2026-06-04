# School Administration System

एक complete web-based school management system जहां students/parents अपना data देख सकें और admins पूरे school को manage कर सकें।

## Features

### 👨‍🎓 Student/Parent Panel
- Login & Signup
- Profile Management
- View Timetable
- Check Exam Schedule
- View Results & Marks
- Pay Fees Online
- Track Attendance
- Submit Feedback

### 👨‍💼 Admin Panel
- Dashboard (Overview)
- Student Management (Add/Edit/Delete)
- Teacher Management
- Class Management
- Subject Management
- Exam Schedule Management
- Result Management
- Fee Collection & Tracking
- Attendance Tracking
- View & Reply to Feedback
- Reports & Analytics

## Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL

## Folder Structure
```
school-administration-system/
├── index.php           # Homepage
├── login.php           # Login page
├── signup.php          # Registration page
├── logout.php          # Logout handler
├── db_config.php       # Database connection
├── assets/
│   └── style.css       # Main stylesheet
├── admin/              # Admin panel files
├── student/            # Student panel files
├── database.sql        # Database schema
└── README.md          # This file
```

## Installation
1. Clone this repository
2. Import `database.sql` in your MySQL
3. Update database credentials in `db_config.php`
4. Run on localhost using XAMPP/WAMP

## Default Credentials
- **Admin:** username: `admin` | password: `admin123`
- **Student:** Create account via signup
