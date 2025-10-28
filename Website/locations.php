<?php
// File: locations.php
include 'db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Locations</title>
</head>
<body>
    <h1>Cruise Destinations</h1>
    <table border="1">
        <tr>
            <th>Location Name</th>
            <th>Description</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM eam_location");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['location_name']}</td>
                        <td>{$row['loc_desc']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No destinations available at the moment</td></tr>";
        }
        ?>
    </table>
    <a href="index.php">Back to Home</a>
</body>
</html>