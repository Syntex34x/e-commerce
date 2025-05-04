<?php
session_start();

// Load existing users
$users = [];
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $emailInput = trim($_POST['email']);
    $email = filter_var($emailInput, FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (!$name || strlen($name) < 2) {
        $error = "Please enter a valid name.";
    } elseif (!$email) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Check if user already exists
        foreach ($users as $user) {
            if ($user['username'] === $email) {
                $error = "User already exists!";
                break;
            }
        }
    }

    if (!isset($error)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Add new user
        $users[] = [
            'name' => $name,
            'username' => $email,
            'password' => $hashedPassword,
            'role' => 'user'
        ];
        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

        // Set session
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'user';
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;

        // Redirect to welcome page
        header('Location: welcome.php?success=1');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
               font-family: 'Segoe UI', sans-serif;
    background: url('bg.png') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
        }
        .signup-container {
            background: rgba(242, 242, 242, 0.59);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 320px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        a {
            color: #5cb85c;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="signup-container">
    <h1>Create Account</h1>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="name" required placeholder="Full Name">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Create Password">
        <input type="password" name="confirm_password" required placeholder="Confirm Password">
        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
