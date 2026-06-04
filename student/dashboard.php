<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

// Get student info
$sql = "SELECT u.*, s.roll_number, s.class_id FROM users u 
        JOIN students s ON u.id = s.user_id 
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    header('Location: ../login.php');
    exit();
}

// Get student's class exams
$exams_sql = "SELECT e.* FROM exams e WHERE e.class_id = ? ORDER BY e.exam_date DESC LIMIT 5";
$exams_stmt = $conn->prepare($exams_sql);
$exams_stmt->bind_param('i', $student['class_id']);
$exams_stmt->execute();
$exams = $exams_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get attendance
$attendance_sql = "SELECT COUNT(*) as total, 
        SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present 
        FROM attendance WHERE student_id = ?";
$attendance_stmt = $conn->prepare($attendance_sql);
$attendance_stmt->bind_param('i', $student['id']);
$attendance_stmt->execute();
$attendance = $attendance_stmt->get_result()->fetch_assoc();

// Get pending fees
$fees_sql = "SELECT SUM(amount) as pending_amount FROM fees WHERE student_id = ? AND status='pending'";
$fees_stmt = $conn->prepare($fees_sql);
$fees_stmt->bind_param('i', $student['id']);
$fees_stmt->execute();
$fees = $fees_stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 Student Portal</h1>
            <nav>
                <a href="#">Welcome, <?php echo $_SESSION['name']; ?></a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <div class="dashboard-header">
                <h2>📊 Your Dashboard</h2>
                <p>Roll Number: <?php echo htmlspecialchars($student['roll_number']); ?></p>
            </div>

            <div class="dashboard-grid">
                <div class="card">
                    <h3>📚 Attendance</h3>
                    <div class="number">
                        <?php 
                        if ($attendance['total'] > 0) {
                            $percentage = ($attendance['present'] / $attendance['total']) * 100;
                            echo round($percentage) . '%';
                        } else {
                            echo '0%';
                        }
                        ?>
                    </div>
                    <p>Present: <?php echo $attendance['present'] ?? 0; ?> / <?php echo $attendance['total'] ?? 0; ?></p>
                    <a href="attendance.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">View Details</a>
                </div>

                <div class="card">
                    <h3>💰 Pending Fees</h3>
                    <div class="number">₹<?php echo $fees['pending_amount'] ?? 0; ?></div>
                    <p>Amount to be paid</p>
                    <a href="fees.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Pay Now</a>
                </div>

                <div class="card">
                    <h3>📝 Exams</h3>
                    <div class="number"><?php echo count($exams); ?></div>
                    <p>Upcoming exams</p>
                    <a href="exams.php" class="btn" style="margin-top: 1rem; display: block; text-align: center;">View</a>
                </div>
            </div>

            <div style="background: white; padding: 2rem; border-radius: 8px; margin-top: 2rem;">
                <h3>Quick Links</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <a href="timetable.php" class="btn">📅 View Timetable</a>
                    <a href="results.php" class="btn">📊 View Results</a>
                    <a href="profile.php" class="btn">👤 My Profile</a>
                    <a href="feedback.php" class="btn">💬 Send Feedback</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>
