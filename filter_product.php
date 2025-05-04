<?php
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Radius of Earth in kilometers

    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $earthRadius * $angle;
}

$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
$category = isset($_POST['category']) ? $_POST['category'] : '';

if ($latitude && $longitude && $category) {

    $jsonFile = $category . '.json';
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $decoded = json_decode($jsonData, true);
        if ($decoded) {
            foreach ($decoded as $product) {
                $productLat = isset($product['latitude']) ? $product['latitude'] : null;
                $productLon = isset($product['longitude']) ? $product['longitude'] : null;

                if ($productLat && $productLon) {
                    $distance = calculateDistance($latitude, $longitude, $productLat, $productLon);
                    if ($distance <= 15) { // Filter products within 15km
                        $img = isset($product['image']) ? htmlspecialchars($product['image']) : 'default.jpg';
                        $name = isset($product['name']) ? htmlspecialchars($product['name']) : 'Unnamed Product';
                        $price = isset($product['price']) ? htmlspecialchars($product['price']) : 'N/A';
                        $details = isset($product['details']) ? htmlspecialchars($product['details']) : '';
                        $owner = isset($product['owner']) ? htmlspecialchars($product['owner']) : 'Unknown';

                        echo '<div class="product-card">';
                        echo '<a href="product_view.php?id=' . htmlspecialchars($product['id']) . '">';
                        echo '<img src="' . $img . '" alt="' . $name . '">';
                        echo '</a>';
                        echo '<h4><a href="product_view.php?id=' . htmlspecialchars($product['id']) . '">' . $name . '</a></h4>';
                        echo '<p>₹' . $price . '</p>';
                        if ($details) echo '<p class="details">' . $details . '</p>';
                        echo '<p class="meta">Owner: ' . $owner . '</p>';

                        echo '<form method="POST" action="cart.php">';
                        echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($product['id']) . '">';
                        echo '<input type="hidden" name="product_name" value="' . $name . '">';
                        echo '<input type="hidden" name="product_price" value="' . $price . '">';
                        echo '<input type="hidden" name="product_image" value="' . $img . '">';
                        echo '<button type="submit">Add to Cart</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                }
            }
        } else {
            echo "No products found!";
        }
    } else {
        echo "File not found!";
    }
} else {
    echo "Location data not provided!";
}
?>