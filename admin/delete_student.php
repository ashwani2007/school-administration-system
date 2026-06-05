<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Delete student from students table
    $conn->query("DELETE FROM students WHERE user_id = $id");
    
    // Delete user
    $conn->query("DELETE FROM users WHERE id = $id");
    
    header('Location: students.php?msg=deleted');
    exit();
}

header('Location: students.php');
exit();
?>
