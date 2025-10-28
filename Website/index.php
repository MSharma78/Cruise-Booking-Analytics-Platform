<?php
// File: index.php
session_start(); // Start session to track logged in users
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NICE Cruise Booking System</title>
    <meta charset="UTF-8">
    <title>NICE Cruise Booking System</title>
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
            background: #ffffffcc;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        /* Add logo as a background image to the header’s left corner */
        header::before {
            content: "";
            display: block;
            background: url('logo.jpg') no-repeat center center;
            background-size: contain;
            width: 100px;
            height: 60px;
            margin-right: 20px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        header p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        /* Stack header text to the right of the logo */
        header > h1, header > p {
            margin-left: 120px; /* leaves space for the logo */
        }

        nav {
            background: rgba(255,255,255,0.9);
            padding: 10px 20px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        nav li {
            margin: 0;
            padding: 0;
        }

        nav a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            transition: background 0.3s;
        }

        nav a:hover {
            background: #ddd;
        }

        /* We have 9 nav items:
           1: Home
           2: About Us
           3: Entertainment & Activities
           4: Places to Eat
           5: Destinations
           6: Book Now
           7: Register with us
           8: Login
           9: Logout
           
           We’ll group them visually using flex and positioning:
           - Left group: items 1-5 (float left)
           - Center item: item 6 (Book Now) centered
           - Right group: items 7-9 (float right)
        */

        nav ul {
            width: 100%;
            justify-content: space-between;
        }

        /* Create three “groups” using flex techniques and nth-child selectors */
        nav ul li:nth-child(1),
        nav ul li:nth-child(2),
        nav ul li:nth-child(3),
        nav ul li:nth-child(4),
        nav ul li:nth-child(5) {
            float: left;
        }

        nav ul li:nth-child(6) {
            margin: 0 auto;
        }

        nav ul li:nth-child(7),
        nav ul li:nth-child(8),
        nav ul li:nth-child(9),
        nav ul li:nth-child(10) {
            float: right;
        }

        /* Clear floats after nav ul */
        nav ul::after {
            content: "";
            display: block;
            clear: both;
        }

        /* Style the “Book Now” link more prominently */
        nav ul li:nth-child(6) a {
            background: #0066cc;
            color: #fff;
            border-radius: 4px;
            font-size: 16px;
        }

        nav ul li:nth-child(6) a:hover {
            background: #005bb5;
        }

        main {
            background: rgba(255,255,255,0.85);
            padding: 30px;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 8px;
            text-align: center;
        }

        main h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #222;
        }

        main p {
            font-size: 16px;
            line-height: 1.5;
            color: #444;
        }

        footer {
            text-align: center;
            background: rgba(255,255,255,0.9);
            padding: 10px;
            font-size: 14px;
            color: #333;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        /* Responsive adjustments if needed */
        @media (max-width: 768px) {
            header h1,
            header p {
                margin-left: 0;
            }
            header::before {
                position: absolute;
                left: 20px;
                top: 20px;
            }
            nav ul {
                flex-direction: column;
            }
            nav li {
                float: none;
                margin: 5px 0;
            }
            main {
                margin: 20px;
            }
        }

        .datavisual-tab {
    position: absolute;
    top: 10px;
    right: 10px; /* Adjust this value to perfectly align */
    background-color: #0066cc;
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    font-weight: bold;
    transition: background 0.3s ease;
}

.datavisual-tab:hover {
    background-color: #005bb5;
}

    </style>
</head>
<body>
    <header>
        <h1>Welcome to NICE Cruise Booking System</h1>
        <p>Nature International Cruise Excellence</p>
        <a href="adminlogin.php" class="datavisual-tab" onclick="return alert('This section is only for administrators!')">Data Visual</a> 
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="activities.php">Entertainment & Activities</a></li>
            <li><a href="restaurants.php">Places to Eat</a></li>
            <li><a href="destinations.php">Destinations</a></li>
            <li><a href="booking.php">Book Now</a></li>
            <li><a href="register.php">Register with us</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="account.php">Account</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h2>About NICE Cruise Lines</h2>
        <p>We offer a wide variety of cruise experiences from multiple ports across the USA, Canada, Mexico, and the Caribbean islands. Enjoy your stay with our luxurious staterooms, fine dining, exciting entertainment, and more!</p>
    </main>

    <footer>
        <p>&copy; 2024 NICE Cruise Booking System. All rights reserved.</p>
    </footer>
</body>
</html>