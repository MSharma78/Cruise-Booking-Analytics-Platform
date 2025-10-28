<?php
// File: login.php
include 'db_connect.php';
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password']; // Password should not be HTML-escaped

    // Prepare variables for the stored procedure
    $output_user_id = null;
    $output_password_hash = null;

    // Call the stored procedure
    $stmt = $conn->prepare("CALL sp_loginUser(?, @output_user_id, @output_password_hash)");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        // Retrieve the output variables
        $result = $conn->query("SELECT @output_user_id AS user_id, @output_password_hash AS password_hash");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $output_user_id = $row['user_id'];
            $output_password_hash = $row['password_hash'];

            // Verify the password using password_verify()
            if ($output_user_id && password_verify($password, $output_password_hash)) {
                // Password is correct, regenerate session ID
                session_regenerate_id(true);
                $_SESSION['user_id'] = $output_user_id; // Store user ID in the session

                // Redirect to the homepage or booking page
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Invalid email or password.";
            }
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Error calling stored procedure: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Your existing styles */
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

        p[style*="color: red;"] {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

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
            input[type="email"], input[type="password"] {
                font-size: 14px;
            }
            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Login to Your Account</h1>
    <?php
    // Display an error message if login fails
    if (isset($error_message)) {
        echo "<p style='color: red;'>" . htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') . "</p>";
    }
    ?>
    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <a href="register.php">Register Here</a>
</body>
</html>
