<?php
$orders = json_decode(file_get_contents('orders.json'), true);
echo json_encode($orders);
?>