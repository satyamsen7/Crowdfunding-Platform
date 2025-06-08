<?php
session_start();

$host = 'localhost';   // Your database host, usually 'localhost'
$user = 'root';        // Your MySQL username
$pass = '';            // Your MySQL password (leave empty for default setup)
$dbname = 'crowdfunding'; // The name of your database

// Create a new MySQLi connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set charset to avoid encoding issues
$conn->set_charset("utf8");

?>

