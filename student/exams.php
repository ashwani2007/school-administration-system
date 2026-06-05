<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

// Get student's class
$student_sql = "SELECT class_id FROM students WHERE user_id = ?";
$student_stmt = $conn->prepare($student_sql);
$student_stmt->bind_param('i', $_SESSION['user_id']);
$student_stmt->execute();
$student = $student_stmt->get_result()->fetch_assoc();

// Get exams for this class
$exams_sql = "SELECT * FROM exams WHERE class_id = ? ORDER BY exam_date DESC";
$exams_stmt = $conn->prepare($exams_sql);
$exams_stmt->bind_param('i', $student['class_id']);
$exams_stmt->execute();
$exams = $exams_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 Student Portal</h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <div class="dashboard-header">
                <h2>📝 Exams Schedule</h2>
            </div>

            <div style="display: grid; gap: 1rem;">
                <?php if (count($exams) > 0): ?>
                    <?php foreach ($exams as $exam): ?>
                        <div style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #667eea;">
                            <h3><?php echo htmlspecialchars($exam['exam_name']); ?></h3>
                            <p><strong>Date:</strong> <?php echo date('d-m-Y', strtotime($exam['exam_date'])); ?></p>
                            <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($exam['exam_time'])); ?></p>
                            <p><strong>Duration:</strong> <?php echo $exam['duration']; ?> hours</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; padding: 2rem;">No exams scheduled yet</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>