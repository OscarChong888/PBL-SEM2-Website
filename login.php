<?php
session_start();

// Include database configuration
include('db_config.php');

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? ''; // Use email instead of username
    $password = $_POST['password'] ?? '';

    // Prepare the SQL query to fetch the user by email
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind email parameter

    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $email;  // Store the email in the session
            header("Location: homepage.php"); // Redirect to homepage
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with that email!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ChessPro</title>
    <link rel="stylesheet" href="styles.css">
    <style>
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
        }

        .back-button:hover {
            background-color: #B28C30;
        }

        body {
            background: url('bgchess.png') no-repeat center center fixed;
            background-size: cover;
        }
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('bgchess.png') no-repeat center center fixed;
            background-size: cover; /* Ensures the image covers the entire screen */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            padding-top: 140px;
        }

        .logo {
            width: calc(100% - 20px);
            max-width: 100%;
            height: auto;
            margin: 20px auto;
            object-fit: contain;
            padding-top: 40px;
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
        }

        .form-group input::placeholder {
            color: #bbb;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #bbb;
            margin-bottom: 20px;
        }

        .options label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .options input[type="checkbox"] {
            margin: 0;
            width: 16px;
            height: 16px;
            accent-color: #00b894; /* Customize checkbox color */
        }

        .options a {
            text-decoration: none;
            color: #C9A23E !important;  /* Gold for "Forgot Password" */
        }

        .options a:hover {
            text-decoration: underline;
            color: #B28C30 !important;  /* Darker gold for hover */
        }

        .signup-link a {
            text-decoration: none;
            color: #4285f4 !important;  /* Light blue for "Sign up" */
        }

        .signup-link a:hover {
            text-decoration: underline;
            color: #3367d6 !important;  /* Slightly darker blue for hover */
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .login-btn {
            background-color: #C9A23E;
            color: #fff;
        }

        .login-btn:hover {
            background-color: #B28C30;
        }

        .apple-btn {
            background-color: #000;
            color: #fff;
        }

        .google-btn {
            background-color: #4285f4;
            color: #fff;
        }

        .divider {
            font-size: 14px;
            color: #bbb;
            margin: 20px 0;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            width: 45%;
            height: 1px;
            background: #444;
            top: 50%;
            transform: translateY(-50%);
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        input[type="email"], input[type="password"] {
            width: 100%; /* Ensures inputs scale properly */
            max-width: 380px; /* Fixes the width */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #333;
            color: #fff;
            font-size: 14px;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #00b894; /* Adds focus color */
            outline: none;
        }
    </style>
</head>
<body>
    <a href="javascript:history.back()" class="back-button">← Back</a>
    <div class="login-wrapper">
        <div class="login-container">
            <img src="chesspro-logo.png" alt="ChessPro Logo" class="logo">
            <?php if ($error_message): ?>
                <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group options">
                    <label>
                        <input type="checkbox" name="remember_me">
                        Remember me
                    </label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" class="btn login-btn">Log In</button>
            </form>
            <div class="signup-link">
                Don't have an account? <a href="signup2.php">Sign up</a>
            </div>
        </div>
    </div>
</body>
</html>
