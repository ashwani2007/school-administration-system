<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$sql = "SELECT * FROM fees WHERE student_id = ? ORDER BY due_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$fees = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate totals
$pending = 0;
$paid = 0;
foreach ($fees as $fee) {
    if ($fee['status'] === 'pending') {
        $pending += $fee['amount'];
    } else {
        $paid += $fee['amount'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees</title>
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
                <h2>💰 Fees Information</h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                <div style="background: #fff3cd; padding: 1.5rem; border-radius: 8px;">
                    <h3>Pending</h3>
                    <p style="font-size: 1.5rem; font-weight: bold;">₹<?php echo $pending; ?></p>
                </div>
                <div style="background: #d4edda; padding: 1.5rem; border-radius: 8px;">
                    <h3>Paid</h3>
                    <p style="font-size: 1.5rem; font-weight: bold;">₹<?php echo $paid; ?></p>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fees as $fee): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fee['description']); ?></td>
                                <td>₹<?php echo $fee['amount']; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($fee['due_date'])); ?></td>
                                <td>
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; 
                                        <?php echo $fee['status'] === 'paid' ? 'background: #d4edda; color: #155724;' : 'background: #fff3cd; color: #856404;'; ?>">
                                        <?php echo ucfirst($fee['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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