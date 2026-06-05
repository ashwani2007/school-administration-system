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
    $roll_number = trim($_POST['roll_number']);
    $class_id = trim($_POST['class_id']);
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);

    if (empty($name) || empty($email) || empty($username) || empty($password) || empty($roll_number) || empty($class_id)) {
        $error = 'All required fields must be filled!';
    } else {
        // Check if username exists
        $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        if ($check->num_rows > 0) {
            $error = 'Username already exists!';
        } else {
            // Insert user
            $hashed_pass = md5($password);
            $user_sql = "INSERT INTO users (name, email, phone, username, password, role) 
                        VALUES ('$name', '$email', '$phone', '$username', '$hashed_pass', 'student')";
            
            if ($conn->query($user_sql)) {
                $user_id = $conn->insert_id;
                
                // Insert student
                $student_sql = "INSERT INTO students (user_id, roll_number, class_id, father_name, mother_name) 
                               VALUES ($user_id, '$roll_number', $class_id, '$father_name', '$mother_name')";
                
                if ($conn->query($student_sql)) {
                    $success = 'Student added successfully!';
                    // Redirect after 2 seconds
                    header('Refresh: 2; URL=students.php');
                } else {
                    $error = 'Error adding student!';
                }
            } else {
                $error = 'Error creating user!';
            }
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
    <title>Add Student</title>
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
                <h2>➕ Add New Student</h2>
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
                        <label for="roll_number">Roll Number *</label>
                        <input type="text" id="roll_number" name="roll_number" required>
                    </div>

                    <div class="form-group">
                        <label for="class_id">Class *</label>
                        <select id="class_id" name="class_id" required>
                            <option value="">Select Class</option>
                            <?php while($row = $classes->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>">
                                    <?php echo $row['class_name'] . ' - ' . $row['section']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="father_name">Father Name</label>
                        <input type="text" id="father_name" name="father_name">
                    </div>

                    <div class="form-group">
                        <label for="mother_name">Mother Name</label>
                        <input type="text" id="mother_name" name="mother_name">
                    </div>

                    <button type="submit" style="width: 100%;">Add Student</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>
