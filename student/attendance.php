<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$sql = "SELECT a.*, DATE_FORMAT(a.attendance_date, '%d-%m-%Y') as date FROM attendance 
        WHERE student_id = ? ORDER BY attendance_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate percentage
$total = count($records);
$present = 0;
foreach ($records as $record) {
    if ($record['status'] === 'present') $present++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
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
                <h2>📚 Attendance Report</h2>
                <p>Overall: <?php echo $total > 0 ? round(($present / $total) * 100) : 0; ?>% (<?php echo $present; ?>/<?php echo $total; ?>)</p>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?php echo $record['date']; ?></td>
                                <td>
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; 
                                        <?php echo $record['status'] === 'present' ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;'; ?>">
                                        <?php echo ucfirst($record['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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