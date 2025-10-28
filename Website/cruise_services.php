<?php
// File: cruise_services.php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['passenger_id'])) {
    echo "Please <a href='login.php'>login</a> to add services to your booking.";
    exit();
}

$passenger_id = $_SESSION['passenger_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_type = $_POST['service_type'];
    $service_id = $_POST['service_id'];
    
    $stmt = $conn->prepare("INSERT INTO eam_cruise_services (booking_id, service_type, service_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $passenger_id, $service_type, $service_id);
    
    if ($stmt->execute()) {
        echo "Service added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Cruise Services</title>
</head>
<body>
    <h1>Add Extra Services</h1>
    <form method="POST" action="">
        <label>Service Type:</label>
        <select name="service_type" required>
            <option value="activity">Activity</option>
            <option value="restaurant">Restaurant</option>
        </select><br><br>

        <label>Service ID:</label>
        <input type="text" name="service_id" required><br><br>

        <input type="submit" value="Add Service">
    </form>
    <a href="index.php">Back to Home</a>
</body>
</html>