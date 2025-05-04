<?php
function displayProductsByCategory($category) {
    $products = json_decode(file_get_contents('products.json'), true);
    foreach ($products as $product) {
        if ($product['category'] === $category) {
            echo '<div class="product-card">';
            echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
            echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
            echo '<p>₹' . htmlspecialchars($product['price']) . '</p>';
            echo '<button>Add to Cart</button>';
            echo '</div>';
        }
    }
}
?>
