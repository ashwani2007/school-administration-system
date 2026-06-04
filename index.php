<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Administration System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 School Administration System</h1>
            <nav>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $_SESSION['role'] === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'; ?>">Dashboard</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Sign Up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <div style="text-align: center; padding: 4rem 0;">
            <h2>Welcome to School Administration System</h2>
            <p style="font-size: 1.1rem; margin: 1rem 0; color: #666;">
                Manage students, teachers, exams, fees, and more efficiently
            </p>
            
            <?php if(!isset($_SESSION['user_id'])): ?>
                <div style="margin-top: 2rem;">
                    <a href="login.php" class="btn" style="margin-right: 1rem;">Login</a>
                    <a href="signup.php" class="btn" style="background: #28a745;">Create Account</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="dashboard-grid" style="margin-top: 3rem;">
            <div class="card">
                <h3>📚 For Students</h3>
                <p>View timetable, exams, results, pay fees, track attendance</p>
            </div>
            <div class="card">
                <h3>👨‍💼 For Admins</h3>
                <p>Manage students, teachers, classes, exams, fees, and reports</p>
            </div>
            <div class="card">
                <h3>🔒 Secure</h3>
                <p>Secure login system with role-based access control</p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>
