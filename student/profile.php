<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$sql = "SELECT u.*, s.roll_number, s.class_id, s.father_name, s.mother_name, c.class_name FROM users u 
        JOIN students s ON u.id = s.user_id 
        LEFT JOIN classes c ON s.class_id = c.id
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
                <h2>👤 My Profile</h2>
            </div>

            <div style="background: white; padding: 2rem; border-radius: 8px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    <div>
                        <h3>Personal Information</h3>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
                    </div>

                    <div>
                        <h3>Academic Information</h3>
                        <p><strong>Roll Number:</strong> <?php echo htmlspecialchars($student['roll_number']); ?></p>
                        <p><strong>Class:</strong> <?php echo htmlspecialchars($student['class_name']); ?></p>
                        <p><strong>Status:</strong> <?php echo ucfirst($student['status']); ?></p>
                    </div>

                    <div>
                        <h3>Parent/Guardian Information</h3>
                        <p><strong>Father Name:</strong> <?php echo htmlspecialchars($student['father_name']); ?></p>
                        <p><strong>Mother Name:</strong> <?php echo htmlspecialchars($student['mother_name']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>