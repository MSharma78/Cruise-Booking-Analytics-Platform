<?php
$servername = "localhost";
$username = "root"; // Default username in XAMPP
$password = ""; // Default password is usually empty for XAMPP
$dbname = "CRUISE"; // Make sure this matches your database name exactly

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// You may remove this debugging line after testing
// echo "Connected successfully to the PROJJJ database";
?>