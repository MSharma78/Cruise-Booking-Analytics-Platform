<?php
// File: account.php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Error: You are not logged in. Please log in to access your account.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql_user = "SELECT * FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

// Handle form submissions for updating username/password //UPDATE UNAME CRUD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_username'])) {
        $new_username = $_POST['username'];

        $sql_update_username = "UPDATE users SET username = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($sql_update_username);
        $stmt_update->bind_param("si", $new_username, $user_id);

        if ($stmt_update->execute()) {
            echo "<p>Username updated successfully!</p>";
        } else {
            echo "<p>Error updating username: " . $stmt_update->error . "</p>";
        }

        $stmt_update->close();                      //UPDATE Password CRUD
    } elseif (isset($_POST['update_password'])) {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql_update_password = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($sql_update_password);
        $stmt_update->bind_param("si", $new_password, $user_id);

        if ($stmt_update->execute()) {
            echo "<p>Password updated successfully!</p>";
        } else {
            echo "<p>Error updating password: " . $stmt_update->error . "</p>";
        }

        $stmt_update->close();        // DELETE CRUD
    } elseif (isset($_POST['delete_account'])) {
        $sql_delete_account = "DELETE FROM users WHERE user_id = ?";
        $stmt_delete = $conn->prepare($sql_delete_account);
        $stmt_delete->bind_param("i", $user_id);

        if ($stmt_delete->execute()) {
            session_destroy();
            echo "<p>Account deleted successfully! Redirecting to homepage...</p>";
            header("Refresh: 3; url=index.php");
            exit();
        } else {
            echo "<p>Error deleting account: " . $stmt_delete->error . "</p>";
        }

        $stmt_delete->close();
    }
}

// Close the prepared statement
$stmt_user->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Management</title>

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

        .account-container {
            background: rgba(255,255,255,0.9);
            padding: 30px;
            margin: 40px auto;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        h3 {
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 20px;
            text-align: center;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
        }

        p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            font-size: 16px;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            font-size: 16px;
            background: #0066cc;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: inline-block;
        }

        button[type="submit"]:hover {
            background: #005bb5;
        }

        /* Special styling for the Delete Account button */
        form[action] button[name="delete_account"] {
            background: #cc0000;
        }

        form[action] button[name="delete_account"]:hover {
            background: #a30000;
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

        hr {
            border: none;
            border-bottom: 1px solid #ccc;
            margin: 30px 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .account-container {
                width: 90%;
            }

            h1 {
                font-size: 20px;
            }

            h3 {
                font-size: 18px;
            }

            input[type="text"], input[type="password"] {
                font-size: 14px;
            }

            button[type="submit"], a {
                font-size: 14px;
            }
        }
    </style>

</head>
<body>
    <h1>Your Account</h1>
    <div class="account-container">
    <h3>Account Details</h3>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

    <hr>
    <h3>Update Username</h3>
    <form method="POST" action="">
        <label for="username">New Username:</label>
        <input type="text" name="username" id="username" required>
        <button type="submit" name="update_username">Update Username</button>
    </form>

    <hr>
    <h3>Update Password</h3>
    <form method="POST" action="">
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" name="update_password">Update Password</button>
    </form>

    <hr>
    <h3>Delete Account</h3>
    <form method="POST" action="">
        <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            Delete Account
        </button>
    </form>

    <hr>
    <a href="index.php">Back to Home</a>
    </div>
</body>
</html>
