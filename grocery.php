<!DOCTYPE html>
<html lang="en">
<head>
    <title>Grocery's Collection - Hexashop</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
   <style>
    body {
    font-family: 'Segoe UI', sans-serif;
   background:white;
    background-size: cover;
    margin: 0;
    padding: 0;
}
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
            gap: 20px;
        }
        .product-card {
            width: 200px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            padding: 15px;
        }
        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-card h4 {
            margin: 10px 0 5px;
        }
        .product-card p {
            color: #555;
        }
        .product-card button {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }
        .product-card button:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>


<main>
    <h2 style="padding: 20px;">Grocery's Collection</h2>
    <div class="product-grid" id="products">
        <?php
        $groceryProducts = [];

        if (file_exists('grocery.json')) {
            $jsonData = file_get_contents('grocery.json');
            $groceryProducts = json_decode($jsonData, true);
        }

        foreach ($groceryProducts as $product) {
            echo '<div class="product-card">';
            echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
            echo '<h4>' . htmlspecialchars($product['name']) . '</h4>';
            echo '<p>₹' . htmlspecialchars($product['price']) . '</p>';
            echo '<form method="POST" action="cart.php">';
            echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($product['id']) . '">';
            echo '<input type="hidden" name="product_name" value="' . htmlspecialchars($product['name']) . '">';
            echo '<input type="hidden" name="product_price" value="' . htmlspecialchars($product['price']) . '">';
            echo '<input type="hidden" name="product_image" value="' . htmlspecialchars($product['image']) . '">';
            echo '<button type="submit">Add to Cart</button>';
            echo '</form>';
            echo '</div>';
        }
        ?>
    </div>
</main>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(sendLocationToServer, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function sendLocationToServer(position) {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "filter_product.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status == 200) {
                document.getElementById("products").innerHTML = xhr.responseText;
            }
        };
        xhr.send("latitude=" + lat + "&longitude=" + lon + "&category=grocery");
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("User  denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }

    getLocation();
</script>
<?php include 'footer.php'; ?>
</body>
</html>