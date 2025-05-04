<?php
session_start();
// Check if the cart and shipping data are set, if not, redirect to the homepage
if (!isset($_SESSION['cart']) || !isset($_SESSION['shipping'])) {
    include 'header.php';
    header("Location: indexs.php");
    exit();
}


// Load user data
$userFile = 'users.json';
$users = file_exists($userFile) ? json_decode(file_get_contents($userFile), true) : [];

// Find the current user from the users array
$currentUser = null;
foreach ($users as $user) {
    if ($user['username'] === $_SESSION['username']) {
        $currentUser = $user;
        break;
    }
}

// Load product data
$productsFile = 'products.json';
$products = file_exists($productsFile) ? json_decode(file_get_contents($productsFile), true) : [];

// Find the shop owner from the products array
$shopOwner = null;
foreach ($_SESSION['cart'] as $item) {
    foreach ($products as $product) {
        if ($product['name'] === $item['name']) {
            $shopOwner = $product['owner'];
            break 2;
        }
    }
}

/// Order setup
$order_no = 'HX' . rand(100000, 999999);
$total = 0;
$product_list = [];

foreach ($_SESSION['cart'] as $item) {
    $subtotal = $item['price'];
    $total += $subtotal;
    $product_list[] = $item['name'] ;
}

$delivery_time = "within 2–3 hours";
$payment_method = $_SESSION['shipping']['payment'] ?? 'Cash on Delivery';

$orderData = [
    'id' => $order_no,
    'user' => $currentUser['username'],
    'total' => $total,
    'products' => $product_list,
    'shipping' => $_SESSION['shipping'],
    'payment_method' => $payment_method,
    'date' => date("Y-m-d H:i:s"),
    'delivery' => $delivery_time,
    'status' => 'Processing',
    'shop_owner' => $shopOwner
];

// Save the order to `orders.json`
$ordersFile = 'orders.json';
$allOrders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];
$allOrders[] = $orderData;
file_put_contents($ordersFile, json_encode($allOrders, JSON_PRETTY_PRINT));

// Clear session data after processing the order
unset($_SESSION['cart']);
unset($_SESSION['shipping']);

// Include header
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success - Hexshop</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <link rel="stylesheet" href="style.css">
    <style>
       body {
    font-family: 'Segoe UI', sans-serif;
    background: url('sucess.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
}

        .container {
            max-width: 650px;
            margin: 60px auto;
            background: hsla(0, 12.70%, 75.30%, 0.77);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            color: #2e7d32;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            color: #444;
            margin: 8px 0;
        }

        .order-details {
            margin-top: 25px;
            text-align: left;
        }

        .order-details strong {
            color: #222;
        }

        .products ul {
            list-style: disc;
            margin: 10px 0 20px 20px;
            padding-left: 0;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 10px 10px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            text-decoration: none;
            transition: background 0.3s ease;
            background-color: #2196f3;
            color: #fff;
        }

        .back-btn:hover {
            background-color: #1976d2;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✅ Order Placed Successfully!</h2>
    <p>Thanks, <?= htmlspecialchars($orderData['user']) ?>! Your order has been placed.</p>

    <div class="order-details">
        <p><strong>Order Number:</strong> <?= $order_no ?></p>
        <p><strong>Date:</strong> <?= date("F j, Y, g:i A", strtotime($orderData['date'])) ?></p>
        <p><strong>Estimated Delivery:</strong> <?= $delivery_time ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($orderData['payment_method']) ?></p>
       <p><strong>Shop Owner:</strong></p>
<div class="shop_owner">
    <p><?= htmlspecialchars($orderData['shop_owner']) ?></p>
</div>
        <p><strong>Products:</strong></p>
        <div class="products">
            <ul>
                <?php foreach ($product_list as $p): ?>
                    <li><?= htmlspecialchars($p) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <p><strong>Total Paid:</strong> ₹<?= number_format($total, 2) ?></p>
    </div>
    <a href="indexs.php" class="back-btn">🛍️ Continue Shopping</a>
</div>
</body>
</html>