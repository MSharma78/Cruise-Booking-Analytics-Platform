<?php
// File: payment.php
include 'db_connect.php';
session_start();

// Retrieve necessary details from session
$invoice_id = isset($_SESSION['invoice_id']) ? $_SESSION['invoice_id'] : null;
$total_amount = isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : null;

if (!$invoice_id || !$total_amount) {
    echo "<p>Error: Booking information missing. Please go back and restart the booking process.</p>";
    exit();
}

// Handle form submission for payment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pmt_method = $_POST['pmt_method'];
    $pmt_amount = $total_amount; // Total amount is set from the invoice

    // Insert payment details into eam_payment table
    $sql_payment = "INSERT INTO eam_payment (pmt_date, pmt_amount, pmt_method, invoice_id) VALUES (NOW(), ?, ?, ?)";
    $stmt_payment = $conn->prepare($sql_payment);

    if ($stmt_payment) {
        $stmt_payment->bind_param("dsi", $pmt_amount, $pmt_method, $invoice_id);

        if ($stmt_payment->execute()) {
            // Redirect to thank_you.php after successful payment
            header("Location: thank_you.php");
            exit();
        } else {
            echo "<p>Error processing payment: " . $stmt_payment->error . "</p>";
        }

        $stmt_payment->close();
    } else {
        echo "<p>Error preparing payment statement: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
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

        .payment-container {
            background: rgba(255,255,255,0.9);
            padding: 30px;
            margin: 40px auto;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            text-align: center;
        }

        h3 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
            text-align: left;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        input[type="radio"] {
            margin-right: 5px;
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
            width: 100%;
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
            margin-top: 20px;
        }

        a:hover {
            background: #0066cc;
            color: #fff;
        }

        @media (max-width: 768px) {
            .payment-container {
                width: 90%;
            }
            input[type="submit"] {
                font-size: 14px;
            }
            label {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Payment Page</h1>
    <div class="payment-container">
    <h3>Total Amount to be Paid: $<?php echo number_format($total_amount, 2); ?></h3>

    <form method="POST" action="">
        <label>Payment Method:</label><br>
        <input type="radio" name="pmt_method" value="Credit Card" required> Credit Card<br>
        <input type="radio" name="pmt_method" value="Debit Card"> Debit Card<br>
        <input type="radio" name="pmt_method" value="PayPal"> PayPal<br>
        <input type="radio" name="pmt_method" value="Bank Transfer"> Bank Transfer<br><br>

        <input type="submit" value="Make Payment">
    </form>
    <a href="index.php">Back to Home</a>
    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
