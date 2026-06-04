<?php
session_start();
require_once '../db_config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get statistics
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_teachers = $conn->query("SELECT COUNT(*) as count FROM teachers")->fetch_assoc()['count'];
$total_classes = $conn->query("SELECT COUNT(*) as count FROM classes")->fetch_assoc()['count'];
$total_exams = $conn->query("SELECT COUNT(*) as count FROM exams")->fetch_assoc()['count'];
$pending_fees = $conn->query("SELECT COUNT(*) as count FROM fees WHERE status='pending'")->fetch_assoc()['count'];
$pending_feedback = $conn->query("SELECT COUNT(*) as count FROM feedback WHERE status='pending'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 Admin Dashboard</h1>
            <nav>
                <a href="#">Welcome, <?php echo $_SESSION['name']; ?></a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <div class="dashboard-header">
                <h2>📊 Overview</h2>
                <p>School Administration Dashboard</p>
            </div>

            <div class="dashboard-grid">
                <div class="card">
                    <h3>👨‍🎓 Students</h3>
                    <div class="number"><?php echo $total_students; ?></div>
                    <a href="students.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Manage</a>
                </div>

                <div class="card">
                    <h3>👨‍🏫 Teachers</h3>
                    <div class="number"><?php echo $total_teachers; ?></div>
                    <a href="teachers.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Manage</a>
                </div>

                <div class="card">
                    <h3>🏢 Classes</h3>
                    <div class="number"><?php echo $total_classes; ?></div>
                    <a href="classes.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Manage</a>
                </div>

                <div class="card">
                    <h3>📝 Exams</h3>
                    <div class="number"><?php echo $total_exams; ?></div>
                    <a href="exams.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Manage</a>
                </div>

                <div class="card">
                    <h3>💰 Pending Fees</h3>
                    <div class="number"><?php echo $pending_fees; ?></div>
                    <a href="fees.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">View</a>
                </div>

                <div class="card">
                    <h3>💬 Feedback</h3>
                    <div class="number"><?php echo $pending_feedback; ?></div>
                    <a href="feedback.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Reply</a>
                </div>
            </div>

            <div style="background: white; padding: 2rem; border-radius: 8px; margin-top: 2rem;">
                <h3>Quick Actions</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <a href="students.php?action=add" class="btn">➕ Add Student</a>
                    <a href="teachers.php?action=add" class="btn">➕ Add Teacher</a>
                    <a href="classes.php?action=add" class="btn">➕ Add Class</a>
                    <a href="exams.php?action=add" class="btn">➕ Add Exam</a>
                    <a href="attendance.php" class="btn">📋 Mark Attendance</a>
                    <a href="reports.php" class="btn">📈 View Reports</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>
