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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $employee_id = trim($_POST['employee_id']);
    $qualification = trim($_POST['qualification']);
    $experience = trim($_POST['experience']);

    if (empty($name) || empty($email) || empty($username) || empty($password) || empty($employee_id)) {
        $error = 'All required fields must be filled!';
    } else {
        $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        if ($check->num_rows > 0) {
            $error = 'Username already exists!';
        } else {
            $hashed_pass = md5($password);
            $user_sql = "INSERT INTO users (name, email, phone, username, password, role) 
                        VALUES ('$name', '$email', '$phone', '$username', '$hashed_pass', 'teacher')";
            
            if ($conn->query($user_sql)) {
                $user_id = $conn->insert_id;
                
                $teacher_sql = "INSERT INTO teachers (user_id, employee_id, qualification, experience) 
                               VALUES ($user_id, '$employee_id', '$qualification', $experience)";
                
                if ($conn->query($teacher_sql)) {
                    $success = 'Teacher added successfully!';
                    header('Refresh: 2; URL=teachers.php');
                } else {
                    $error = 'Error adding teacher!';
                }
            } else {
                $error = 'Error creating user!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
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
                <h2>➕ Add New Teacher</h2>
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
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="employee_id">Employee ID *</label>
                        <input type="text" id="employee_id" name="employee_id" required>
                    </div>

                    <div class="form-group">
                        <label for="qualification">Qualification</label>
                        <input type="text" id="qualification" name="qualification" placeholder="B.A, B.Sc, M.A etc">
                    </div>

                    <div class="form-group">
                        <label for="experience">Experience (Years)</label>
                        <input type="number" id="experience" name="experience">
                    </div>

                    <button type="submit" style="width: 100%;">Add Teacher</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>