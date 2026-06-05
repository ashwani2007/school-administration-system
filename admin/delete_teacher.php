<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn->query("DELETE FROM teachers WHERE user_id = $id");
    $conn->query("DELETE FROM users WHERE id = $id");
    
    header('Location: teachers.php?msg=deleted');
    exit();
}

header('Location: teachers.php');
exit();
?>