<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';
$class = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM classes WHERE id = $id");
    $class = $result->fetch_assoc();
    
    if (!$class) {
        header('Location: classes.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $class_name = trim($_POST['class_name']);
    $section = trim($_POST['section']);
    $teacher_id = trim($_POST['teacher_id']);

    if (empty($class_name) || empty($section)) {
        $error = 'Class name and section are required!';
    } else {
        $sql = "UPDATE classes SET class_name='$class_name', section='$section', teacher_id=" . (!empty($teacher_id) ? $teacher_id : 'NULL') . " WHERE id=$id";
        
        if ($conn->query($sql)) {
            $success = 'Class updated successfully!';
            header('Refresh: 2; URL=classes.php');
        } else {
            $error = 'Error updating class!';
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
    <title>Edit Class</title>
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
                <h2>✏️ Edit Class</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($class): ?>
            <div class="form-container" style="max-width: 600px;">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $class['id']; ?>">

                    <div class="form-group">
                        <label for="class_name">Class Name *</label>
                        <input type="text" id="class_name" name="class_name" value="<?php echo htmlspecialchars($class['class_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="section">Section *</label>
                        <input type="text" id="section" name="section" value="<?php echo htmlspecialchars($class['section']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="teacher_id">Class Teacher</label>
                        <select id="teacher_id" name="teacher_id">
                            <option value="">Select Teacher</option>
                            <?php while($row = $teachers->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo $row['id'] == $class['teacher_id'] ? 'selected' : ''; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button type="submit" style="width: 100%;">Update Class</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>