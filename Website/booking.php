<?php
// File: booking.php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_type = $_POST['group_type'];
    $total_adults = $_POST['total_adults'];
    $total_children = $_POST['total_children'];
    $trip_id = $_POST['trip_id'];
    $sr_loc_id = $_POST['sr_loc_id'];
    $user_id = $_SESSION['user_id']; // user_id is saved in session during login

    // Start a transaction
    $conn->begin_transaction();

    try {
        // FOR UPDATE to trip query to prevent deadlocks
        $trip_sql = "
            SELECT t.trip_id, t.start_date, t.end_date, sp.pr_name AS start_port, ep.pr_name AS end_port
            FROM eam_trip t
            JOIN eam_port sp ON t.start_port_id = sp.port_id
            JOIN eam_port ep ON t.end_port_id = ep.port_id
            WHERE t.trip_id = ?
            FOR UPDATE";
        $stmt_trip = $conn->prepare($trip_sql);
        $stmt_trip->bind_param("i", $trip_id);
        $stmt_trip->execute();
        $trip_result = $stmt_trip->get_result();

        if ($trip_result->num_rows === 0) {
            throw new Exception("Invalid trip ID.");
        }

        // FOR UPDATE to stateroom location query to prevent deadlocks
        $stateroom_sql = "
            SELECT esl.sr_loc_id, es.type AS stateroom_type, el.location_name, etsp.price_per_night
            FROM eam_stateroom_location esl
            JOIN eam_stateroom es ON es.stateroom_id = esl.stateroom_id
            JOIN eam_location el ON el.location_id = esl.location_id
            JOIN eam_trip_strm_price etsp ON etsp.sr_loc_id = esl.sr_loc_id
            WHERE esl.sr_loc_id = ?
            FOR UPDATE";
        $stmt_stateroom = $conn->prepare($stateroom_sql);
        $stmt_stateroom->bind_param("i", $sr_loc_id);
        $stmt_stateroom->execute();
        $stateroom_result = $stmt_stateroom->get_result();

        if ($stateroom_result->num_rows === 0) {
            throw new Exception("Invalid stateroom location ID.");
        }

        // Insert group into eam_group table
        $sql_group = "INSERT INTO eam_group (group_type, total_adults, total_children, user_id) VALUES (?, ?, ?, ?)";
        $stmt_group = $conn->prepare($sql_group);
        $stmt_group->bind_param("siii", $group_type, $total_adults, $total_children, $user_id);

        if (!$stmt_group->execute()) {
            throw new Exception("Error creating group: " . $stmt_group->error);
        }

        $group_id = $stmt_group->insert_id;

        // Insert booking into eam_booking table
        $sql_booking = "INSERT INTO eam_booking (group_id, trip_id, sr_loc_id) VALUES (?, ?, ?)";
        $stmt_booking = $conn->prepare($sql_booking);
        $stmt_booking->bind_param("iii", $group_id, $trip_id, $sr_loc_id);

        if (!$stmt_booking->execute()) {
            throw new Exception("Error creating booking: " . $stmt_booking->error);
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to passenger_details.php with group_id and passenger count
        header("Location: passenger_details.php?group_id=$group_id&total_adults=$total_adults&total_children=$total_children");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    } finally {
        // Close all prepared statements
        if (isset($stmt_trip)) $stmt_trip->close();
        if (isset($stmt_stateroom)) $stmt_stateroom->close();
        if (isset($stmt_group)) $stmt_group->close();
        if (isset($stmt_booking)) $stmt_booking->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Your Cruise Now</title>
    <style>
        /* Styles remain unchanged */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        h1 {
            position: relative;
            background: rgba(255,255,255,0.85);
            padding: 20px;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        h1::before {
            content: "";
            display: block;
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 100px;
            height: 60px;
            background: url('logo.jpg') no-repeat center center;
            background-size: contain;
        }

        form {
            background: rgba(255,255,255,0.9);
            padding: 30px;
            margin: 100px auto 0 auto;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        select {
            font-size: 16px;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            font-size: 16px;
            background: #0066cc;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background: #005bb5;
        }

        a {
            display: inline-block;
            text-decoration: none;
            color: #0066cc;
            font-weight: bold;
            background: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            border: 1px solid #0066cc;
            transition: background 0.3s, color 0.3s;
            margin: 20px auto;
            text-align: center;
        }

        a:hover {
            background: #0066cc;
            color: #fff;
        }

        /* Adjust form width, font-size, and spacing on smaller screens */
        @media (max-width: 768px) {
            form {
                margin: 50px auto 0 auto;
                width: 90%;
            }

            label {
                font-size: 14px;
            }

            input[type="text"], 
            input[type="number"], 
            select {
                font-size: 14px;
            }

            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Book Your Cruise Now</h1>
    <form method="POST" action="">
        <label>Group Type:</label>
        <input type="text" name="group_type" required><br><br>
        <label>Total Adults:</label>
        <input type="number" name="total_adults" required><br><br>
        <label>Total Children:</label>
        <input type="number" name="total_children" required><br><br>
        <label>Trip:</label>
        <select name="trip_id" required>
        <option value="">-- Select Trip --</option>
            <?php
            $trip_sql = "
                SELECT 
                    t.trip_id, 
                    t.start_date, 
                    t.end_date, 
                    sp.pr_name AS start_port, 
                    ep.pr_name AS end_port
                FROM eam_trip t
                JOIN eam_port sp ON t.start_port_id = sp.port_id
                JOIN eam_port ep ON t.end_port_id = ep.port_id";
            $trip_result = $conn->query($trip_sql);

            while ($trip = $trip_result->fetch_assoc()) {
                $start_date = date('Y-m-d', strtotime($trip['start_date']));
                $end_date = date('Y-m-d', strtotime($trip['end_date']));
                echo "<option value='{$trip['trip_id']}'>
                        {$trip['start_port']} to {$trip['end_port']} | {$start_date} - {$end_date}
                      </option>";
            }
            ?>
        </select><br><br>
        <label>Stateroom Location:</label>
        <select name="sr_loc_id" required>
            <option value="">-- Select Stateroom Location --</option>
            <?php
            $stateroom_sql = "SELECT esl.sr_loc_id, es.type AS stateroom_type, el.location_name, etsp.price_per_night 
                              FROM eam_stateroom_location esl
                              JOIN eam_stateroom es ON es.stateroom_id = esl.stateroom_id
                              JOIN eam_location el ON el.location_id = esl.location_id
                              JOIN eam_trip_strm_price etsp ON etsp.sr_loc_id = esl.sr_loc_id
                              ORDER BY esl.sr_loc_id";
            $stateroom_result = $conn->query($stateroom_sql);

            if ($stateroom_result->num_rows > 0) {
                while ($row = $stateroom_result->fetch_assoc()) {
                    echo "<option value='{$row['sr_loc_id']}'>
                              {$row['stateroom_type']} - {$row['location_name']} - \${$row['price_per_night']} per night
                          </option>";
                }
            } else {
                echo "<option value=''>No staterooms available</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Book Now">
    </form>
    <a href="index.php">Back to Home</a>
</body>
</html>
