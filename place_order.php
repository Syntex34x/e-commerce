<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Handle the order placement process
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order = [
        'id' => uniqid('HX'), // Unique order ID
        'user' => $_SESSION['username'], // The logged-in user's name
        'total' => $_POST['total'], // Total order amount
        'products' => $_POST['products'], // Ordered products
        'shipping' => [
            'name' => $_POST['shipping_name'],
            'address' => $_POST['shipping_address'],
            'pincode' => $_POST['shipping_pincode'],
            'mobile' => $_POST['shipping_mobile']
        ],
        'payment_method' => $_POST['payment_method'],
        'date' => date('Y-m-d H:i:s'),
        'delivery' => $_POST['delivery_time'],
        'status' => 'Processing',
        'shop_owner' => 'unknown' // Can be updated later
    ];

    // Load existing orders
    $ordersFile = 'orders.json';
    $orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];

    // Add the new order to the orders list
    $orders[] = $order;

    // Save the updated orders back to the file
    file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));

    // Redirect to the order confirmation page
    header('Location: track_order.php?order_id=' . urlencode($order['id']));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order - Hexshop</title>
</head>
<body>

<h2>Place Your Order</h2>

<form action="place_order.php" method="POST">
    <label for="products">Products (comma separated):</label>
    <input type="text" name="products" required>
    <br>
    <label for="total">Total Amount:</label>
    <input type="number" name="total" required>
    <br>
    <label for="shipping_name">Name:</label>
    <input type="text" name="shipping_name" required>
    <br>
    <label for="shipping_address">Address:</label>
    <input type="text" name="shipping_address" required>
    <br>
    <label for="shipping_pincode">Pincode:</label>
    <input type="text" name="shipping_pincode" required>
    <br>
    <label for="shipping_mobile">Mobile:</label>
    <input type="text" name="shipping_mobile" required>
    <br>
    <label for="payment_method">Payment Method:</label>
    <select name="payment_method">
        <option value="Cash on Delivery">Cash on Delivery</option>
        <option value="Online Payment">Online Payment</option>
    </select>
    <br>
    <label for="delivery_time">Estimated Delivery Time:</label>
    <input type="text" name="delivery_time" value="within 2–3 hours" required>
    <br>
    <button type="submit">Place Order</button>
</form>

</body>
</html>
