<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>✅ test_db.php is running</h2>";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CRUISE"; // must match your MySQL Workbench schema name exactly

echo "<p>Attempting connection to database: <b>$dbname</b></p>";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("<p style='color:red;'>❌ Connection failed: " . $conn->connect_error . "</p>");
}

echo "<p style='color:green;'>✅ Connected successfully to <b>$dbname</b></p>";

$conn->close();
?>
