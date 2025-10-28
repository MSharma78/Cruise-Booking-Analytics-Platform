<?php
// File: activities.php
include 'db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activities Onboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        /* Style the heading similar to other pages.
           We'll use a pseudo-element to display the logo, similar to previous pages.
        */
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

        /* Table styling */
        table {
            border-collapse: collapse;
            margin: 40px auto;
            width: 80%;
            background: rgba(255,255,255,0.9);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            text-align: left;
        }

        table th, table td {
            padding: 15px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        /* Link styling to match theme */
        a {
            display: inline-block;
            margin: 20px auto;
            background: #fff;
            padding: 10px 20px;
            color: #0066cc;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            border: 1px solid #0066cc;
            transition: background 0.3s, color 0.3s;
            text-align: center;
        }

        a:hover {
            background: #0066cc;
            color: #fff;
        }

        /* Adjust for responsiveness */
        @media (max-width: 768px) {
            table {
                width: 95%;
            }

            h1::before {
                width: 60px;
                height: 40px;
            }

            table th, table td {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Onboard Activities</h1>
    <table border="1">
        <tr>
            <th>Activity Name</th>
            <th>Floor</th>
            <th>Units Available</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM eam_activity");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['a_name']}</td>
                        <td>{$row['act_floor']}</td>
                        <td>{$row['a_unit']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No activities available at the moment</td></tr>";
        }
        ?>
    </table>
    <a href="index.php">Back to Home</a>
</body>
</html>