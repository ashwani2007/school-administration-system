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

// Get timetable
$timetable_sql = "SELECT t.*, s.subject_name, u.name as teacher_name FROM timetable t 
                  LEFT JOIN subjects s ON t.subject_id = s.id
                  LEFT JOIN users u ON t.teacher_id = u.id
                  WHERE t.class_id = ? ORDER BY t.day, t.start_time";
$timetable_stmt = $conn->prepare($timetable_sql);
$timetable_stmt->bind_param('i', $student['class_id']);
$timetable_stmt->execute();
$timetable = $timetable_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable</title>
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
                <h2>📅 Class Timetable</h2>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($timetable) > 0): ?>
                            <?php foreach ($timetable as $slot): ?>
                                <tr>
                                    <td><?php echo ucfirst($slot['day']); ?></td>
                                    <td><?php echo htmlspecialchars($slot['subject_name']); ?></td>
                                    <td><?php echo htmlspecialchars($slot['teacher_name']); ?></td>
                                    <td><?php echo date('h:i A', strtotime($slot['start_time'])) . ' - ' . date('h:i A', strtotime($slot['end_time'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem;">No timetable available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>