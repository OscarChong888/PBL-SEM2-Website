<?php
session_start();
require 'db_config.php'; // Include database configuration

$error_message = "";
$elo_rating = 400; // Default rating

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $skill_level = $_SESSION['skill_level'] ?? 'Unknown';

    // Set ELO rating based on skill level
    switch ($skill_level) {
        case 'Beginner':
        case 'New to Chess':
            $elo_rating = 400;
            break;
        case 'Intermediate':
            $elo_rating = 800;
            break;
        case 'Advanced':
            $elo_rating = 1200;
            break;
        default:
            $elo_rating = 400; // Default for "Unknown"
            break;
    }

    // Validation
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long!";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email is already registered!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (email, password, skill_level, elo_rating) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $email, $hashed_password, $skill_level, $elo_rating);

            if ($stmt->execute()) {
                $_SESSION['email'] = $email;
                $_SESSION['elo_rating'] = $elo_rating;
                header("Location: homepage.php"); // Redirect to homepage
                exit();
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ChessPro</title>
    <style>
        /* General Styles */
        body {
            background: url('bgchess.png') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .login-container {
            background: #2c2c2c;
            border-radius: 8px;
            padding: 30px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .logo {
            width: calc(100% - 20px);
            max-width: 100%;
            height: auto;
            margin: 20px auto;
            object-fit: contain;
        }

        .error-message {
            background: #ff4d4d;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background: #333;
            color: #fff;
            font-size: 16px;
            max-width: 380px;
        }

        .form-group input::placeholder {
            color: #bbb;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-btn {
            background-color: #C9A23E;
            color: #fff;
        }

        .login-btn:hover {
            background-color: #B28C30;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #C9A23E;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #B28C30;
        }
    </style>
</head>
<body>
    <a href="signup.php" class="back-button">← Back</a>
    <div class="login-wrapper">
        <div class="login-container">
            <img src="chesspro-logo.png" alt="ChessPro Logo" class="logo">
            <h1>Sign Up</h1>
            <p>Your skill level: <strong><?= htmlspecialchars($_SESSION['skill_level'] ?? 'Unknown') ?></strong></p>
            <?php if ($error_message): ?>
                <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Create Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn login-btn">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>
