<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['name'];
$success = isset($_GET['success']) && $_GET['success'] == 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #83a4d4, #b6fbff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .welcome-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .profile-info {
            font-size: 18px;
            margin-top: 10px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #5cb85c;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        a:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
<div class="welcome-container">
    <?php if ($success): ?>
        <div class="success">🎉 Account created successfully!</div>
    <?php endif; ?>
    <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
    <div class="profile-info">
        You are now logged in as a user.
    </div>
    <a href="indexs.php">Go to Homepage</a>
</div>
</body>
</html>
