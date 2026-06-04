<?php
// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "school_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

?>
