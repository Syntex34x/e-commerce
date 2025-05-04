<?php
session_start();

// This should be set right after account creation
if (!isset($_SESSION['new_owner'])) {
    header("Location: login.php");
    exit;
}

$owner = $_SESSION['new_owner'];
unset($_SESSION['new_owner']); // One-time use
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Account Created | Hexshop</title>
  <style>
    body {
      background: #f4f7fa;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .card {
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 500px;
    }

    .card h1 {
      color: #2ecc71;
      margin-bottom: 20px;
    }

    .card p {
      font-size: 18px;
      margin: 10px 0;
      color: #333;
    }

    .button {
      margin-top: 25px;
      padding: 12px 20px;
      background: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      display: inline-block;
    }

    .button:hover {
      background: #2980b9;
    }
  </style>
</head>
<body>
  <div class="card">
    <h1>Account Created Successfully!</h1>
    <p><strong>Username:</strong> <?= htmlspecialchars($owner['username']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($owner['role']) ?></p>
    <p><strong>Created At:</strong> <?= htmlspecialchars($owner['created_at']) ?></p>
    <a href="shop_owner.php" class="button">Go to Shop Owner Dashboard</a>
  </div>
</body>
</html>
