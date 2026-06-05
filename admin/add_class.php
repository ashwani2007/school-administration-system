<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = trim($_POST['class_name']);
    $section = trim($_POST['section']);
    $teacher_id = trim($_POST['teacher_id']);

    if (empty($class_name) || empty($section)) {
        $error = 'Class name and section are required!';
    } else {
        $sql = "INSERT INTO classes (class_name, section, teacher_id) 
                VALUES ('$class_name', '$section', " . (!empty($teacher_id) ? $teacher_id : 'NULL') . ")";
        
        if ($conn->query($sql)) {
            $success = 'Class added successfully!';
            header('Refresh: 2; URL=classes.php');
        } else {
            $error = 'Error adding class!';
        }
    }
}

$teachers = $conn->query("SELECT id, name FROM users WHERE role = 'teacher' ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 Admin Panel</h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="classes.php">Classes</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <div class="dashboard-header">
                <h2>➕ Add New Class</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="form-container" style="max-width: 600px;">
                <form method="POST">
                    <div class="form-group">
                        <label for="class_name">Class Name *</label>
                        <input type="text" id="class_name" name="class_name" placeholder="e.g., 10th, 12th" required>
                    </div>

                    <div class="form-group">
                        <label for="section">Section *</label>
                        <input type="text" id="section" name="section" placeholder="e.g., A, B, C" required>
                    </div>

                    <div class="form-group">
                        <label for="teacher_id">Class Teacher</label>
                        <select id="teacher_id" name="teacher_id">
                            <option value="">Select Teacher</option>
                            <?php while($row = $teachers->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button type="submit" style="width: 100%;">Add Class</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>