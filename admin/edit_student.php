<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';
$student = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT u.*, s.roll_number, s.class_id FROM users u 
                           JOIN students s ON u.id = s.user_id WHERE u.id = $id");
    $student = $result->fetch_assoc();
    
    if (!$student) {
        header('Location: students.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $roll_number = trim($_POST['roll_number']);
    $class_id = trim($_POST['class_id']);
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);

    if (empty($name) || empty($email) || empty($roll_number) || empty($class_id)) {
        $error = 'All required fields must be filled!';
    } else {
        // Update user
        $user_sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$id";
        
        if ($conn->query($user_sql)) {
            // Update student
            $student_sql = "UPDATE students SET roll_number='$roll_number', class_id=$class_id, 
                           father_name='$father_name', mother_name='$mother_name' WHERE user_id=$id";
            
            if ($conn->query($student_sql)) {
                $success = 'Student updated successfully!';
                header('Refresh: 2; URL=students.php');
            } else {
                $error = 'Error updating student!';
            }
        } else {
            $error = 'Error updating user!';
        }
    }
}

// Get all classes
$classes = $conn->query("SELECT * FROM classes ORDER BY class_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 Admin Panel</h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="students.php">Students</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <div class="dashboard-header">
                <h2>✏️ Edit Student</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($student): ?>
            <div class="form-container" style="max-width: 600px;">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="roll_number">Roll Number *</label>
                        <input type="text" id="roll_number" name="roll_number" value="<?php echo htmlspecialchars($student['roll_number']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="class_id">Class *</label>
                        <select id="class_id" name="class_id" required>
                            <option value="">Select Class</option>
                            <?php while($row = $classes->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo $row['id'] == $student['class_id'] ? 'selected' : ''; ?>>
                                    <?php echo $row['class_name'] . ' - ' . $row['section']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="father_name">Father Name</label>
                        <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($student['father_name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="mother_name">Mother Name</label>
                        <input type="text" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($student['mother_name']); ?>">
                    </div>

                    <button type="submit" style="width: 100%;">Update Student</button>
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
