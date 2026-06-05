<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn->query("DELETE FROM classes WHERE id = $id");
    
    header('Location: classes.php?msg=deleted');
    exit();
}

header('Location: classes.php');
exit();
?>