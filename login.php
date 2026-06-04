<?php
session_start();
require_once 'db_config.php';

$error = '';
$role = isset($_GET['role']) ? $_GET['role'] : 'student'; // Default to student

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $login_role = trim($_POST['role']);

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required!';
    } else {
        $sql = "SELECT * FROM users WHERE username = ? AND role = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $login_role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (md5($password) === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: student/dashboard.php');
                }
                exit();
            } else {
                $error = 'Invalid password!';
            }
        } else {
            $error = 'Username not found for this role!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Administration System</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .role-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .role-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .role-btn:hover {
            border-color: #667eea;
        }

        .role-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 School Administration System</h1>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>Login</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Role Selection -->
            <div class="role-selector">
                <button type="button" class="role-btn <?php echo $role === 'student' ? 'active' : ''; ?>" onclick="switchRole('student')">
                    👨‍🎓 Student
                </button>
                <button type="button" class="role-btn <?php echo $role === 'admin' ? 'active' : ''; ?>" onclick="switchRole('admin')">
                    🧑‍💼 Admin
                </button>
            </div>

            <form method="POST" id="loginForm">
                <input type="hidden" name="role" id="roleInput" value="<?php echo htmlspecialchars($role); ?>">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Login</button>
            </form>

            <p style="margin-top: 1.5rem; text-align: center;">
                Don't have an account? <a href="signup.php" style="color: #667eea;">Sign Up here</a>
            </p>

            <!-- Demo Credentials -->
            <div style="background: #f0f0f0; padding: 1rem; margin-top: 1.5rem; border-radius: 4px;">
                <p style="font-size: 0.9rem; margin-bottom: 0.75rem;"><strong>📌 Demo Credentials:</strong></p>
                
                <p style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong>Admin Login:</strong></p>
                <p style="font-size: 0.85rem; margin-bottom: 1rem;">
                    Username: <code>admin</code> | Password: <code>admin123</code>
                </p>

                <p style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong>Student Login:</strong></p>
                <p style="font-size: 0.85rem;">
                    Create account via <a href="signup.php" style="color: #667eea;">Sign Up</a>
                </p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>

    <script>
        function switchRole(selectedRole) {
            document.getElementById('roleInput').value = selectedRole;
            
            // Update button styles
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update form action
            document.getElementById('loginForm').submit();
        }
    </script>
</body>
</html>
