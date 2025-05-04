<?php
session_start();
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Checkout - Hexshop</title>
    <style>
       body {
    font-family: 'Segoe UI', sans-serif;
    background: url('checkout.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
}

        .container {
            max-width: 700px;
            margin: 60px auto;
            background:hsla(0, 12.70%, 75.30%, 0.77); 
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px hsla(0, 72.70%, 4.30%, 0.77);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        ul {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }
        li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        li img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 15px;
            border: 1px solid #ddd;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-details strong {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .item-details span {
            color: #666;
            font-size: 14px;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            color: #2c3e50;
        }
        .btn {
            display: block;
            width: 100%;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #1a252f;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Order Summary</h2>
    <ul>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['qty'];
            $total += $subtotal;
            echo "<li>";
            if (!empty($item['image'])) {
                echo "<img src='" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['name']) . "'>";
            }
            echo "<div class='item-details'>
                    <strong>" . htmlspecialchars($item['name']) . "</strong>
                    <span>₹{$item['price']} x {$item['qty']} = ₹{$subtotal}</span>
                  </div>";
            echo "</li>";
        }
        ?>
    </ul>
    <div class="total">Total: ₹<?php echo $total; ?></div>
    <form action="address.php" method="post">
        <button type="submit" class="btn">Continue to Address</button>
    </form>
</div>
</body>
</html>
