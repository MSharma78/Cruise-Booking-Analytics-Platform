<?php
// File: register.php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password for security

    // Check if email already exists
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo "This email is already registered. Please use another email or <a href='login.php'>login here</a>.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            echo "Registration successful! Redirecting to login...";
            header("Refresh: 2; url=login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register with Us</title>
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
            margin: 100px auto 0 auto;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
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

        @media (max-width: 768px) {
            form {
                margin: 50px auto 0 auto;
                width: 90%;
            }
            input[type="text"], input[type="email"], input[type="password"] {
                font-size: 14px;
            }
            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Register with NICE Cruise Lines</h1>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>
    <a href="index.php">Back to Home</a>
</body>
</html>
