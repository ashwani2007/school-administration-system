<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $category = trim($_POST['category']);

    if (empty($subject) || empty($message) || empty($category)) {
        $error = 'All fields are required!';
    } else {
        $sql = "INSERT INTO feedback (student_id, subject, message, category, status, created_at) 
                VALUES (?, ?, ?, ?, 'new', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $_SESSION['user_id'], $subject, $message, $category);
        
        if ($stmt->execute()) {
            $success = 'Feedback submitted successfully!';
        } else {
            $error = 'Error submitting feedback!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Feedback</title>
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
                <h2>💬 Send Feedback</h2>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-container" style="max-width: 600px;">
                <form method="POST">
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="academic">Academic</option>
                            <option value="facilities">Facilities</option>
                            <option value="teachers">Teachers</option>
                            <option value="management">Management</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>

                    <button type="submit" style="width: 100%;">Submit Feedback</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>