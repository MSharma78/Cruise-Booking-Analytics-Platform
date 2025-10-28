<?php
// File: packages.php
include 'db_connect.php';
session_start();

// Ensure group_id is retained in the session
if (!isset($_SESSION['group_id'])) {
    echo "Error: Group information missing. Please go back and start the booking process again.";
    exit();
}

// Retrieve passenger details from the session
$group_id = $_SESSION['group_id'];
$total_passengers = isset($_SESSION['total_passengers']) ? $_SESSION['total_passengers'] : null;

if (!$total_passengers) {
    // Fetch the total passengers from the eam_group table
    $sql_passenger_count = "SELECT total_adults + total_children AS total_passengers FROM eam_group WHERE group_id = ?";
    $stmt_passenger_count = $conn->prepare($sql_passenger_count);
    $stmt_passenger_count->bind_param("i", $group_id);
    $stmt_passenger_count->execute();
    $result_passenger_count = $stmt_passenger_count->get_result();
    if ($result_passenger_count->num_rows > 0) {
        $row = $result_passenger_count->fetch_assoc();
        $total_passengers = $row['total_passengers'];
        $_SESSION['total_passengers'] = $total_passengers; // Save total passengers to session
    } else {
        echo "Error: Unable to retrieve group information.";
        exit();
    }
    $stmt_passenger_count->close();
}

// Handle form submission for package selection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    for ($i = 1; $i <= $total_passengers; $i++) {
        if (isset($_POST["passenger_id_$i"])) {
            $passenger_id = $_POST["passenger_id_$i"];
            if (isset($_POST["packages_$i"])) {
                foreach ($_POST["packages_$i"] as $package_id) {
                    $quantity = $_POST["quantity_{$i}_{$package_id}"];

                    // Insert selected package details into eam_passenger_package
                    $sql = "INSERT INTO eam_passenger_package (passenger_id, package_id, quantity) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if ($stmt === false) {
                        echo "Error preparing statement: " . $conn->error;
                        exit();
                    }

                    $stmt->bind_param("iii", $passenger_id, $package_id, $quantity);

                    if (!$stmt->execute()) {
                        echo "Error adding package for passenger $passenger_id: " . $stmt->error;
                        exit();
                    }

                    $stmt->close();
                }
            }
        }
    }

    // Set booking ID to session for use in invoice.php
    if (!isset($_SESSION['booking_id'])) {
        $sql_booking = "SELECT booking_id FROM eam_booking WHERE group_id = ?";
        $stmt_booking = $conn->prepare($sql_booking);
        $stmt_booking->bind_param("i", $group_id);
        $stmt_booking->execute();
        $result_booking = $stmt_booking->get_result();
        if ($result_booking->num_rows > 0) {
            $booking_data = $result_booking->fetch_assoc();
            $_SESSION['booking_id'] = $booking_data['booking_id'];
        } else {
            echo "Error: Booking information could not be retrieved.";
            exit();
        }
        $stmt_booking->close();
    }

    // Redirect to invoice.php after packages are successfully added
    header("Location: invoice.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Packages for Passengers</title>
    <style>
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
            margin: 40px auto;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        h2 {
            background: #f5f5f5;
            padding: 10px;
            margin: 30px 0 20px 0;
            border-radius: 4px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        label {
            font-weight: bold;
            display: inline-block;
            margin-bottom: 5px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        input[type="number"] {
            width: 60px;
            margin-left: 5px;
            font-size: 14px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Apply some spacing between package options */
        label + label, label + input[type="number"] {
            margin-top: 10px;
            display: block;
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
            margin-top: 20px;
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
            text-align: center;
            margin-top: 20px;
        }

        a:hover {
            background: #0066cc;
            color: #fff;
        }

        /* Adjust spacing for smaller screens */
        @media (max-width: 768px) {
            form {
                width: 90%;
            }
            h2 {
                font-size: 18px;
            }
            input[type="number"] {
                width: 50px;
            }
        }
    </style>
</head>
<body>
    <h1>Select Packages for Passengers</h1>
    <form method="POST" action="">
        <?php
        $result_passenger = $conn->query("SELECT * FROM eam_passenger WHERE group_id = $group_id");
        
        if ($result_passenger && $result_passenger->num_rows > 0) {
            $passenger_index = 1;
            while ($passenger = $result_passenger->fetch_assoc()) {
                $passenger_id = $passenger['passenger_id'];
                echo "<h2>Packages for Passenger: " . htmlspecialchars($passenger['p_fname']) . " " . htmlspecialchars($passenger['p_lname']) . "</h2>";
                echo "<input type='hidden' name='passenger_id_$passenger_index' value='" . htmlspecialchars($passenger_id) . "'>";

                // Get available packages
                $result_package = $conn->query("SELECT * FROM eam_package");
                if ($result_package && $result_package->num_rows > 0) {
                    while ($package = $result_package->fetch_assoc()) {
                        $package_id = htmlspecialchars($package['package_id']);
                        $package_name = htmlspecialchars($package['package_name']);
                        $unit_price = htmlspecialchars($package['unit_price']);

                        echo "<label>";
                        echo "<input type='checkbox' name='packages_{$passenger_index}[]' value='{$package_id}'> {$package_name} (\${$unit_price})";
                        echo "</label><br>";
                        echo "<label>Quantity: </label> <input type='number' name='quantity_{$passenger_index}_{$package_id}' min='1' value='1'><br><br>";
                    }
                } else {
                    echo "No packages available at the moment.<br>";
                }
                $passenger_index++;
            }
        } else {
            echo "<p>No passengers available to assign packages.</p>";
        }
        ?>

        <input type="submit" value="Add Packages">
    </form>
    <a href="index.php">Back to Home</a>
</body>
</html>
