<?php
// File: invoice.php
include 'db_connect.php';
session_start();

// Validate session variables
if (!isset($_SESSION['group_id'])) {
    echo "Error: Group ID not found.";
    exit();
}

$group_id = $_SESSION['group_id'];

// Fetch group details
$sql_group = "SELECT * FROM eam_group WHERE group_id = ?";
$stmt_group = $conn->prepare($sql_group);
$stmt_group->bind_param("i", $group_id);
$stmt_group->execute();
$group = $stmt_group->get_result()->fetch_assoc();

// Fetch trip details
$sql_trip = "SELECT t.trip_id, t.start_date, t.end_date, t.total_nights 
             FROM eam_trip t
             JOIN eam_booking b ON t.trip_id = b.trip_id
             WHERE b.group_id = ?";
$stmt_trip = $conn->prepare($sql_trip);
$stmt_trip->bind_param("i", $group_id);
$stmt_trip->execute();
$trip = $stmt_trip->get_result()->fetch_assoc();

// Fetch stateroom and location details and calculate total price
$sql_stateroom = "SELECT s.type, l.location_name, p.price_per_night
                  FROM eam_stateroom s
                  JOIN eam_stateroom_location sl ON s.stateroom_id = sl.stateroom_id
                  JOIN eam_location l ON sl.location_id = l.location_id
                  JOIN eam_trip_strm_price p ON sl.sr_loc_id = p.sr_loc_id
                  JOIN eam_booking b ON p.sr_loc_id = b.sr_loc_id
                  WHERE b.group_id = ?";

$stmt_stateroom = $conn->prepare($sql_stateroom);
$stmt_stateroom->bind_param("i", $group_id);
$stmt_stateroom->execute();
$stateroom = $stmt_stateroom->get_result()->fetch_assoc();

// Fetch passenger details
$sql_passengers = "SELECT * FROM eam_passenger WHERE group_id = ?";
$stmt_passengers = $conn->prepare($sql_passengers);
$stmt_passengers->bind_param("i", $group_id);
$stmt_passengers->execute();
$passengers = $stmt_passengers->get_result();

// Fetch package details
$sql_packages = "SELECT pp.quantity, p.package_name, p.unit_price
                    FROM eam_passenger_package pp
                    JOIN eam_package p ON p.package_id = pp.package_id
                    WHERE pp.passenger_id IN (
                          SELECT pa.passenger_id
                             FROM eam_passenger pa
                                 WHERE pa.group_id = ?)";
$stmt_packages = $conn->prepare($sql_packages);
$stmt_packages->bind_param("i", $group_id);
$stmt_packages->execute();
$packages = $stmt_packages->get_result();

// Calculate total amount
$total_stateroom_cost = $stateroom['price_per_night'] * $trip['total_nights'];
$total_packages_cost = 0;

while ($package = $packages->fetch_assoc()) {
    $total_packages_cost += $package['unit_price'] * $package['quantity'];
}

$total_amount = $total_stateroom_cost + $total_packages_cost;

// Check if the invoice already exists for this booking
$sql_check_invoice = "SELECT * FROM eam_invoice WHERE booking_id = ?";
$stmt_check_invoice = $conn->prepare($sql_check_invoice);
$booking_id = $trip['trip_id'];
$stmt_check_invoice->bind_param("i", $booking_id);
$stmt_check_invoice->execute();
$result_check_invoice = $stmt_check_invoice->get_result();

if ($result_check_invoice->num_rows == 0) {
    // Insert Invoice Data into eam_invoice table if it doesn't exist
    $sql_insert_invoice = "INSERT INTO eam_invoice (booking_id, total_amt) VALUES (?, ?)";
    $stmt_invoice = $conn->prepare($sql_insert_invoice);
    $stmt_invoice->bind_param("id", $booking_id, $total_amount);

    if (!$stmt_invoice->execute()) {
        echo "Error inserting invoice: " . $stmt_invoice->error;
        exit();
    }

    $invoice_id = $stmt_invoice->insert_id;
} else {
    $invoice = $result_check_invoice->fetch_assoc();
    $invoice_id = $invoice['invoice_id'];
}

// Store data in session
$_SESSION['invoice_id'] = $invoice_id;
$_SESSION['total_amount'] = $total_amount;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
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

        /* Container for invoice details */
        .invoice-container {
            background: rgba(255,255,255,0.9);
            padding: 30px;
            margin: 40px auto;
            max-width: 700px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        h2 {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            font-size: 20px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        hr {
            border: none;
            border-bottom: 1px solid #ccc;
            margin: 20px 0;
        }

        .total-section {
            background: #fafafa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: center;
        }

        .total-section h3 {
            font-size: 22px;
            margin-bottom: 10px;
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
            margin-top: 20px;
            text-align: center;
        }

        a:hover {
            background: #0066cc;
            color: #fff;
        }

        @media (max-width: 768px) {
            .invoice-container {
                width: 90%;
                margin: 20px auto;
            }

            p {
                font-size: 14px;
            }

            h2 {
                font-size: 18px;
            }

            .total-section h3 {
                font-size: 20px;
            }

            a {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Booking Invoice</h1>
    <div class="invoice-container">

    <!-- Display group, trip, stateroom, passenger, and package details -->
    <h2>Group Information</h2>
    <p>Group Type: <?php echo $group['group_type']; ?></p>
    <p>Total Adults: <?php echo $group['total_adults']; ?></p>
    <p>Total Children: <?php echo $group['total_children']; ?></p>

    <h2>Trip Details</h2>
    <p>Trip ID: <?php echo $trip['trip_id']; ?></p>
    <p>Start Date: <?php echo $trip['start_date']; ?></p>
    <p>End Date: <?php echo $trip['end_date']; ?></p>
    <p>Total Nights: <?php echo $trip['total_nights']; ?></p>

    <h2>Stateroom Details</h2>
    <p>Stateroom Type: <?php echo $stateroom['type']; ?></p>
    <p>Location: <?php echo $stateroom['location_name']; ?></p>
    <p>Price per Night: $<?php echo number_format($stateroom['price_per_night'], 2); ?></p>
    <p>Total Stateroom Cost: $<?php echo number_format($total_stateroom_cost, 2); ?></p>

    <h2>Packages Taken</h2>
    <?php
    $stmt_packages->execute(); // Re-fetch packages
    $packages = $stmt_packages->get_result();
    if ($packages->num_rows > 0) {
        while ($package = $packages->fetch_assoc()) { ?>
            <p>Package Name: <?php echo $package['package_name']; ?></p>
            <p>Quantity: <?php echo $package['quantity']; ?></p>
            <p>Cost: $<?php echo number_format($package['unit_price'] * $package['quantity'], 2); ?></p>
            <hr>
    <?php }
    } else {
        echo "<p>No packages selected.</p>";
    }
    ?>
    <div class="total-section">
    <h2>Total Cost</h2>
    <p>Total Amount: $<?php echo number_format($total_amount, 2); ?></p>

    <a href="payment.php">Proceed to Payment</a>
</div>
</div>
</body>
</html>
