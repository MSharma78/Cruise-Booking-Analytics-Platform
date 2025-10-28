<?php
// File: passenger_details.php
include 'db_connect.php';
session_start();

// Checking if total_adults and total_children were passed in the URL, otherwise retrieve from session
if (isset($_GET['total_adults']) && isset($_GET['total_children'])) {
    $_SESSION['total_adults'] = $_GET['total_adults'];
    $_SESSION['total_children'] = $_GET['total_children'];
    $_SESSION['group_id'] = $_GET['group_id'];
}

// Retrieve values from session to make sure they are always available
$total_adults = isset($_SESSION['total_adults']) ? $_SESSION['total_adults'] : 0;
$total_children = isset($_SESSION['total_children']) ? $_SESSION['total_children'] : 0;
$group_id = isset($_SESSION['group_id']) ? $_SESSION['group_id'] : null;

if ($group_id === null) {
    echo "Error: Group ID not found.";
    exit();
}

$total_passengers = $total_adults + $total_children;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert passenger information for each group member
    for ($i = 1; $i <= $total_passengers; $i++) {
        // Assuming that the input field names are like fname_1, lname_1 for passenger 1, etc.
        $fname = $_POST["fname_$i"];
        $lname = $_POST["lname_$i"];
        $dob = $_POST["dob_$i"];
        $gender = $_POST["gender_$i"];
        $nationality = $_POST["nationality_$i"];
        $email = $_POST["email_$i"];
        $phone = $_POST["phone_$i"];
        $passport_no = $_POST["passport_no_$i"];
        $street = $_POST["street_$i"];
        $city = $_POST["city_$i"];
        $state = $_POST["state_$i"];
        $country = $_POST["country_$i"];
        $zip = $_POST["zip_$i"];

        $sql = "INSERT INTO eam_passenger (group_id, p_fname, p_lname, p_dob, p_gender, p_nationality, p_email, p_phone, p_passport_no, p_street, p_city, p_states, p_country, p_zip)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssssssssss", $group_id, $fname, $lname, $dob, $gender, $nationality, $email, $phone, $passport_no, $street, $city, $state, $country, $zip);

        if (!$stmt->execute()) {
            echo "Error adding passenger $i: " . $stmt->error;
            exit();
        }
    }

    echo "All passengers added successfully! Redirecting to package selection...";
    header("Refresh: 2; url=packages.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Passengers</title>
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
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        label {
            display: inline-block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
            padding: 10px;
            margin-bottom: 15px;
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
            text-align: center;
            margin-top: 20px;
        }

        a:hover {
            background: #0066cc;
            color: #fff;
        }

        /* Ensure spacing between passenger sections */
        h2:not(:first-child) {
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            form {
                width: 90%;
            }
            input[type="text"], input[type="date"] {
                font-size: 14px;
            }
            input[type="submit"] {
                font-size: 14px;
            }
            h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <h1>Add Passenger Information</h1>
    <form method="POST" action="">
        <?php
        for ($i = 1; $i <= $total_passengers; $i++) {
            echo "<h2>Passenger $i</h2>";
            echo '<label>First Name:</label> <input type="text" name="fname_'.$i.'" required><br>';
            echo '<label>Last Name:</label> <input type="text" name="lname_'.$i.'" required><br>';
            echo '<label>Date of Birth:</label> <input type="date" name="dob_'.$i.'" required><br>';
            echo '<label>Gender:</label> <input type="text" name="gender_'.$i.'" required><br>';
            echo '<label>Nationality:</label> <input type="text" name="nationality_'.$i.'" required><br>';
            echo '<label>Email:</label> <input type="text" name="email_'.$i.'" required><br>';
            echo '<label>Phone:</label> <input type="text" name="phone_'.$i.'" required><br>';
            echo '<label>Passport Number:</label> <input type="text" name="passport_no_'.$i.'" required><br>';
            echo '<label>Street:</label> <input type="text" name="street_'.$i.'" required><br>';
            echo '<label>City:</label> <input type="text" name="city_'.$i.'" required><br>';
            echo '<label>State:</label> <input type="text" name="state_'.$i.'" required><br>';
            echo '<label>Country:</label> <input type="text" name="country_'.$i.'" required><br>';
            echo '<label>Zip Code:</label> <input type="text" name="zip_'.$i.'" required><br><br>';
        }
        ?>
        <input type="submit" value="Add Passengers">
    </form>
    <a href="index.php">Back to Home</a>
</body>
</html>
