<?php
session_start();

// Admin credentials
$adminEmail = 'admin@example.com';
$adminPassword = 'admin123';
$adminName = 'Admin';
$adminMobile = '9026559040'; // Admin mobile for OTP

// Delivery Boy credentials (you can replace with actual logic or database)
$deliveryBoyEmail = 'delivery@example.com';
$deliveryBoyPassword = 'delivery123';
$deliveryBoyName = 'John Doe';
$deliveryBoyMobile = '9876543210'; // Delivery boy mobile for OTP

// Load users from the JSON file
$users = [];
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $input = $_POST['email']; // Email or username input
    $password = $_POST['password']; // User inputted password
    $loginType = $_POST['login_type']; // Login type (admin, shop_owner, user, delivery_boy)

    // Admin login logic
    if ($loginType === 'admin') {
        if (($input === $adminEmail || $input === 'admin') && $password === $adminPassword) {
            // Set session data for admin
            $_SESSION['otp_for'] = 'admin';
            $_SESSION['name'] = $adminName;
            $_SESSION['username'] = 'admin';
            $_SESSION['email'] = $adminEmail;
            $_SESSION['mobile'] = $adminMobile;

            // Redirect to OTP page
            header("Location: send_otp.php");
            exit;
        } else {
            $error = "Invalid admin credentials.";
        }
    } 
    // Shop Owner or User login logic
    elseif ($loginType === 'shop_owner' || $loginType === 'user') {
        // Loop through the users to validate credentials
        foreach ($users as $user) {
            $match = ($user['email'] === $input || $user['username'] === $input);
            if ($match && password_verify($password, $user['password'])) {
                if ($user['role'] === $loginType) {
                    // Set session data for shop owner
                    if ($loginType === 'shop_owner') {
                        $_SESSION['otp_for'] = 'shop_owner';
                        $_SESSION['name'] = $user['name'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['mobile'] = $user['mobile'];

                        header("Location: send_otp.php");
                        exit;
                    } else {
                        // Normal user login
                        $_SESSION['loggedin'] = true;
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['name'] = $user['name'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];

                        // Redirect to homepage
                        header("Location: indexs.php");
                        exit;
                    }
                } else {
                    $error = "Role mismatch!";
                }
            }
        }

        if (empty($error)) $error = "Invalid credentials.";
    } 
    // Delivery Boy login logic
    elseif ($loginType === 'delivery_boy') {
        if (($input === $deliveryBoyEmail || $input === 'delivery_boy') && $password === $deliveryBoyPassword) {
            // Set session data for delivery boy
            $_SESSION['otp_for'] = 'delivery_boy';
            $_SESSION['name'] = $deliveryBoyName;
            $_SESSION['username'] = 'delivery_boy';
            $_SESSION['email'] = $deliveryBoyEmail;
            $_SESSION['mobile'] = $deliveryBoyMobile;

            // Redirect to OTP page
            header("Location: send_otp.php");
            exit;
        } else {
            $error = "Invalid delivery boy credentials.";
        }
    } 
    else {
        $error = "Invalid login type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hexshop</title>
    <style>
        body {
            background-color: rgba(242, 242, 242, 0.59);
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background:white;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .login-container {
            background: rgba(242, 242, 242, 0.59); 
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        input, select {
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

        .signup-link {
            text-align: center;
            margin-top: 10px;
        }

        .signup-link a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <label for="login_type">Login As:</label>
        <select name="login_type" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="shop_owner">Shop Owner</option>
            <option value="user">User</option>
            <option value="delivery_boy">Delivery Boy</option>
        </select>
        <input type="text" name="email" required placeholder="Email or Username">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Login</button>
    </form>
    <div class="signup-link">
        Don't have an account? <a href="signup.php">Sign up</a>
    </div>
</div>
</body>
</html>
