<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

$order_id = $_POST['order_id'] ?? '';
$new_status = $_POST['status'] ?? '';

if (empty($order_id) || empty($new_status)) {
    echo "Order ID and new status are required.";
    exit();
}

$ordersFile = 'orders.json';
$orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];

$updated = false;
foreach ($orders as &$order) {
    if ($order['id'] === $order_id) {
        $order['status'] = $new_status;
        $updated = true;
        break;
    }
}

if ($updated) {
    file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));
    header("Location: track_order.php?order_id=" . urlencode($order_id));
    exit();
} else {
    echo "Order not found.";
}
?>
