<?php
// Database credentials
$servername = "localhost";
$username = "oecapps";
$password = "0906852@aA"; // Default XAMPP password
$dbname = "crestabank"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Optionally, you can set the character set for the connection
$conn->set_charset("utf8mb4"); 

?>