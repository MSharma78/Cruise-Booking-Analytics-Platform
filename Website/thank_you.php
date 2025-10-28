<?php
// File: thank_you.php
session_start();

// Clear the session variables to reset the booking process
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('lastbackground.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff; /* Bright text for dark background */
            text-align: center;
        }

        h1 {
            position: relative;
            background: rgba(0,0,0,0.7);
            padding: 20px;
            margin: 0;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
        }

        /* Add a subtle logo placeholder if desired (similar to previous pages) 
           If no logo is needed, you can omit this block 
           If you have a logo.png, uncomment below:
        
        h1::before {
            content: "";
            display: block;
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 100px;
            height: 60px;
            background: url('logo.png') no-repeat center center;
            background-size: contain;
        }
        */

        p {
            background: rgba(0,0,0,0.6);
            display: inline-block;
            padding: 20px;
            margin: 40px auto 20px auto;
            font-size: 18px;
            line-height: 1.5;
            border-radius: 8px;
        }

        a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            background: rgba(255,255,255,0.1);
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 20px;
            border: 1px solid #fff;
            transition: background 0.3s, color 0.3s;
        }

        a:hover {
            background: #fff;
            color: #000;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
            }
            p {
                width: 90%;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <h1>Thank You for Your Booking!</h1>
    <p>Your payment has been successfully processed, and your booking is confirmed.</p><br>
    <a href="index.php">Return to Home</a>
</body>
</html>