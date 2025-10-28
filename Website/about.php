<?php
// File: about.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - NICE Cruise Lines</title>
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

        /* Logo in header (just like on the main page, using a pseudo-element) */
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

        /* Stack header text with space for logo */
        header > h1, header > p {
            margin-left: 120px; /* adjusting for the logo space */
        }

        main {
            background: rgba(255,255,255,0.85);
            padding: 30px;
            margin: 40px auto;
            max-width: 800px;
            border-radius: 8px;
            text-align: center;
        }

        main p {
            font-size: 16px;
            line-height: 1.5;
            color: #444;
            margin-bottom: 20px;
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
            padding: 5px 10px;
            border-radius: 4px;
            background: #fff;
            transition: background 0.3s;
        }

        footer a:hover {
            background: #eee;
        }

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
            main {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>About NICE Cruise Lines</h1>
        <p>Nature International Cruise Excellence</p>
    </header>
    <main>
        <p>NICE Cruise Lines is a luxury cruise company offering a wide range of unique experiences. We have the most luxurious staterooms, the finest dining, and exciting activities for all age groups.</p>
        <p>Our cruises sail from multiple ports across the USA, Canada, Mexico, and the Caribbean islands.</p>
    </main>
    <footer>
        <a href="index.php">Back to Home</a>
    </footer>
</body>
</html>
