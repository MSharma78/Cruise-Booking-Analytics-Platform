<?php
// File: group_booking.php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = $_POST['group_name'];
    $total_adults = $_POST['total_adults'];
    $total_children = $_POST['total_children'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Insert into eam_group table
    $sql = "INSERT INTO eam_group (group_name, total_adults, total_children, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siii", $group_name, $total_adults, $total_children, $user_id);

    if ($stmt->execute()) {
        // Redirect to the add_passengers page to add detailed information for each passenger
        header("Location: add_passengers.php?group_id=" . $stmt->insert_id . "&total_adults=$total_adults&total_children=$total_children");
        exit();
    } else {
        echo "Error creating group: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create or Select Group</title>
</head>
<body>
    <h1>Create Group Booking</h1>
    <form method="POST" action="">
        <label>Group Name:</label>
        <input type="text" name="group_name" required><br><br>
        <label>Total Adults:</label>
        <input type="number" name="total_adults" required><br><br>
        <label>Total Children:</label>
        <input type="number" name="total_children" required><br><br>
        <input type="submit" value="Create Group">
    </form>
    <a href="index.php">Back to Home</a>
</body>
</html>