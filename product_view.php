<?php
// product_view.php

$productId = $_GET['id'] ?? null;
$product = null;
$category = null;

if ($productId) {
    $fileNames = ['kids.json', 'restaurants.json', 'grocery.json', 'medicine.json', 'electrical.json']; // Define all possible file names to check
    foreach ($fileNames as $fileName) {
        if (file_exists($fileName)) {
            $data = json_decode(file_get_contents($fileName), true);
            foreach ($data as $item) {
                if ($item['id'] == $productId) {
                    $product = $item;
                    $category = pathinfo($fileName, PATHINFO_FILENAME);
                    break 2; // Exit both loops once product is found
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product View</title>
    <style>
        /* Your styles here */
    </style>
</head>
<body>

<div class="product-view">
    <?php if ($product): ?>
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p class="price">₹<?= htmlspecialchars($product['price']) ?></p>
        <p class="details"><?= htmlspecialchars($product['details']) ?></p>
        <p>Owner: <?= htmlspecialchars($product['owner']) ?></p>
        <p>Added on: <?= htmlspecialchars($product['created_at']) ?></p>

        <form method="POST" action="cart.php">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
            <input type="hidden" name="product_price" value="<?= htmlspecialchars($product['price']) ?>">
            <input type="hidden" name="product_image" value="<?= htmlspecialchars($product['image']) ?>">
            <label>Qty:</label>
            <select name="quantity">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select><br>
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

    <?php else: ?>
        <p>Product not found!</p>
    <?php endif; ?>

    <?php if ($category): ?>
        <a href="<?= strtolower($category) ?>.php" class="back-link">← Back to <?= ucfirst($category) ?> Collection</a>
    <?php endif; ?>
</div>


    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .product-view {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .product-view img {
            max-width: 100%;
            border-radius: 10px;
        }
        .product-view h2 {
            margin-top: 10px;
        }
        .product-view .details {
            margin: 10px 0;
            font-style: italic;
            color: #555;
        }
        .product-view .price {
            font-size: 1.2em;
            font-weight: bold;
            color: #e67e22;
        }
        .product-view button {
            margin-top: 10px;
            padding: 10px 15px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .back-link {
            margin-top: 15px;
            display: inline-block;
        }
  
  
</style>

</body>
</html>
