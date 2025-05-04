<?php

include 'header.php';


// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'] ?? '';
    $name = $_POST['product_name'] ?? '';
    $price = $_POST['product_price'] ?? 0;
    $image = $_POST['product_image'] ?? '';

    // Check if product is already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $id) {
            $item['qty'] += $_POST['quantity']; // Adding quantity as specified
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'qty' => $_POST['quantity'] ?? 1
        ];
    }

    header("Location: cart.php");
    exit;
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($removeId) {
        return $item['id'] !== $removeId;
    });
    header("Location: cart.php");
    exit;
}

// Handle update quantity
if (isset($_POST['update_qty'])) {
    $id = $_POST['product_id'];
    $qty = $_POST['qty'];

    // Update the quantity in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $id) {
            $item['qty'] = $qty;
            break;
        }
    }

    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Cart - Hexshop</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('cart.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .cart-container {
            max-width: 800px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 15px;
        }
        .cart-item img {
            width: 100px;
            height: auto;
            border-radius: 10px;
        }
        .cart-item-details {
            flex: 1;
        }
        .cart-item-actions {
            text-align: right;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
        }
        .btn-remove {
            background-color: crimson;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-checkout {
            margin-top: 20px;
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .update-qty {
            width: 50px;
            text-align: center;
        }
        @media screen and (max-width: 600px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }
            .cart-item-actions {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <?php $total = 0; ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <?php $total += $item['price'] * $item['qty']; ?>
            <form method="POST" action="cart.php">
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="cart-item-details">
                        <h4><?= htmlspecialchars($item['name']) ?></h4>
                        <p>Price: ₹<?= $item['price'] ?> x <?= $item['qty'] ?></p>
                    </div>
                    <div class="cart-item-actions">
                        <a href="cart.php?remove=<?= urlencode($item['id']) ?>" class="btn-remove">Remove</a>
                        <div>
                            <input type="number" name="qty" class="update-qty" value="<?= $item['qty'] ?>" min="1">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="update_qty" class="btn-checkout" style="margin-top: 10px;">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endforeach; ?>
        <div class="cart-total">Total: ₹<?= $total ?></div>
        <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>
