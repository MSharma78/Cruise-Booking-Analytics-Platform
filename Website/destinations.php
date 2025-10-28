<?php
// File: destinations.php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Destinations</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        header {
            position: relative;
            background: rgba(255,255,255,0.85);
            padding: 20px;
            text-align: center;
        }

        header::before {
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

        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        main {
            background: rgba(255,255,255,0.9);
            margin: 40px auto;
            padding: 20px;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            font-size: 16px;
        }

        table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        footer {
            text-align: center;
            background: rgba(255,255,255,0.9);
            padding: 15px;
            font-size: 14px;
            color: #333;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer a {
            text-decoration: none;
            color: #0066cc;
            font-weight: bold;
            background: #fff;
            padding: 8px 15px;
            border-radius: 4px;
            border: 1px solid #0066cc;
            transition: background 0.3s, color 0.3s;
        }

        footer a:hover {
            background: #0066cc;
            color: #fff;
        }

        @media (max-width: 768px) {
            main {
                margin: 20px;
            }

            table th, table td {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Our Cruise Destinations</h1>
    </header>
    <main>
        <table border="1">
            <tr>
                <th>Port Name</th>
                <th>Terminal</th>
                <th>Nearest Airport</th>
                <th>Parking Spots</th>
                <th>Street</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
                <th>Country</th>
            </tr>
            
            <?php
        $query = "
            SELECT 
                p.pr_name AS port_name,
                pa.pr_terminal,
                p.pr_nearest_airport,
                p.pr_parking_spots,
                pa.pr_street,
                pa.pr_city,
                pa.pr_states,
                pa.pr_zip,
                pa.pr_country
            FROM eam_port p
            JOIN eam_port_address pa ON p.address_id = pa.address_id
        ";

        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['port_name']}</td>
                        <td>{$row['pr_terminal']}</td>
                        <td>{$row['pr_nearest_airport']}</td>
                        <td>{$row['pr_parking_spots']}</td>
                        <td>{$row['pr_street']}</td>
                        <td>{$row['pr_city']}</td>
                        <td>{$row['pr_states']}</td>
                        <td>{$row['pr_zip']}</td>
                        <td>{$row['pr_country']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No destinations found</td></tr>";
        }
        ?>


        </table>
    </main>
    <footer>
        <a href="index.php">Back to Home</a>
    </footer>
</body>
</html>
