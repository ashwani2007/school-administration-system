<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';
$teacher = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT u.*, t.employee_id, t.qualification, t.experience FROM users u 
                           JOIN teachers t ON u.id = t.user_id WHERE u.id = $id");
    $teacher = $result->fetch_assoc();
    
    if (!$teacher) {
        header('Location: teachers.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $employee_id = trim($_POST['employee_id']);
    $qualification = trim($_POST['qualification']);
    $experience = trim($_POST['experience']);

    if (empty($name) || empty($email) || empty($employee_id)) {
        $error = 'All required fields must be filled!';
    } else {
        $user_sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$id";
        
        if ($conn->query($user_sql)) {
            $teacher_sql = "UPDATE teachers SET employee_id='$employee_id', qualification='$qualification', 
                           experience=$experience WHERE user_id=$id";
            
            if ($conn->query($teacher_sql)) {
                $success = 'Teacher updated successfully!';
                header('Refresh: 2; URL=teachers.php');
            } else {
                $error = 'Error updating teacher!';
            }
        } else {
            $error = 'Error updating user!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 Admin Panel</h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="teachers.php">Teachers</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <div class="dashboard-header">
                <h2>✏️ Edit Teacher</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($teacher): ?>
            <div class="form-container" style="max-width: 600px;">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $teacher['id']; ?>">

                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($teacher['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($teacher['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($teacher['phone']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="employee_id">Employee ID *</label>
                        <input type="text" id="employee_id" name="employee_id" value="<?php echo htmlspecialchars($teacher['employee_id']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="qualification">Qualification</label>
                        <input type="text" id="qualification" name="qualification" value="<?php echo htmlspecialchars($teacher['qualification']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="experience">Experience (Years)</label>
                        <input type="number" id="experience" name="experience" value="<?php echo $teacher['experience']; ?>">
                    </div>

                    <button type="submit" style="width: 100%;">Update Teacher</button>
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