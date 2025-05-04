<?php
session_start();

// Check if OTP is set in the session
if (!isset($_SESSION['otp'])) {
    echo "❌ OTP is not set. Please request a new OTP.";
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputOtp = $_POST['otp'];

    // Verify OTP entered by the user
    if ($inputOtp == $_SESSION['otp']) {
        // OTP is correct, log the user in based on their session role
        if ($_SESSION['otp_for'] === 'admin') {
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'admin';
            header("Location: admin_home.php"); // Redirect to admin dashboard
            exit;
        } elseif ($_SESSION['otp_for'] === 'shop_owner') {
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'shop_owner';
            header("Location: shop_owner.php"); // Redirect to shop owner dashboard
            exit;
        } elseif ($_SESSION['otp_for'] === 'user') {
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'user';
            header("Location: user_dashboard.php"); // Redirect to user dashboard
            exit;
        } elseif ($_SESSION['otp_for'] === 'delivery_boy') {
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'delivery_boy';
            header("Location: delivery_partner.php"); // Redirect to delivery boy dashboard
            exit;
        }
    } else {
        // OTP is incorrect
        $error = "❌ Invalid OTP entered. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Hexshop</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .verify-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
<div class="verify-container">
    <h2>Verify OTP</h2>
    <?php if (!empty($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
    <form method="POST">
        <input type="text" name="otp" required placeholder="Enter OTP" pattern="\d+" title="OTP must be a number">
        <button type="submit">Verify OTP</button>
    </form>
</div>
</body>
</html>
