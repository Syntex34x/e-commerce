<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}



// Load users from JSON
$usersFile = 'users.json';
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    if (!$users) {
        $users = [];
    }
} else {
    $users = [];
}

// Load orders from JSON
$ordersFile = 'orders.json';
if (file_exists($ordersFile)) {
    $orders = json_decode(file_get_contents($ordersFile), true);
    if (!$orders) {
        $orders = [];
    }
} else {
    $orders = [];
}

// Get current user's username
$username = $_SESSION['username'];

// Find the current user
$currentUser = null;
foreach ($users as $user) {
    if ($user['username'] === $username) {
        $currentUser = $user;
        break;
    }
}

if (!$currentUser) {
    echo "User not found!";
    exit();
}

// Filter orders that belong to the current user
$userOrders = array_filter($orders, function($order) use ($username) {
    return $order['user'] === $username;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .orderProduct {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .orderProduct ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .orderProduct li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h2>Profile</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($currentUser['name']) ?></p>
    <p><strong>Username:</strong> <?= htmlspecialchars($currentUser['username']) ?></p>

    <h2>Your Orders</h2>
    <?php if (!empty($userOrders)): ?>
        <?php foreach ($userOrders as $order): ?>
            <div class="orderProduct">
                <p><strong>Product:</strong> 
                    <ul>
                        <?php foreach ($order['products'] as $product): ?>
                            <li><?= htmlspecialchars($product) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </p>
                <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                <p><strong>Total:</strong> ₹<?= number_format($order['total'], 2) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>
</body>
</html>