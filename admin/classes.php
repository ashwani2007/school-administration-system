<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get all classes
$result = $conn->query("SELECT c.*, u.name as teacher_name FROM classes c 
                       LEFT JOIN users u ON c.teacher_id = u.id 
                       ORDER BY c.class_name, c.section");
$classes = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes Management</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏫 Admin Panel</h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <div class="dashboard-header">
                <h2>🏢 Class Management</h2>
                <a href="add_class.php" class="btn">➕ Add New Class</a>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Section</th>
                            <th>Class Teacher</th>
                            <th>Total Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($classes) > 0): ?>
                            <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                                    <td><?php echo htmlspecialchars($class['section']); ?></td>
                                    <td><?php echo $class['teacher_name'] ? htmlspecialchars($class['teacher_name']) : 'Not Assigned'; ?></td>
                                    <td><?php echo $class['total_students']; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit_class.php?id=<?php echo $class['id']; ?>" class="btn btn-small btn-edit">Edit</a>
                                            <a href="delete_class.php?id=<?php echo $class['id']; ?>" class="btn btn-small btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">No classes found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Administration System. All rights reserved.</p>
    </footer>
</body>
</html>
