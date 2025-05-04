<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    // Proceed
} else {
    header("Location: cart.php");
    exit();
    include 'header.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Address - Hexshop</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
    <style>
       body {
    font-family: 'Segoe UI', sans-serif;
    background: url('address.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
}

        .container {
            max-width: 650px;
            margin: 60px auto;
            background:rgba(255, 255, 255, 0.56);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        form input, form textarea {
            width: 100%;
            padding: 14px 16px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        form input:focus, form textarea:focus {
            border-color: #43a047;
            box-shadow: 0 0 8px rgba(67, 160, 71, 0.3);
        }
        .btn {
            width: 100%;
            background: #43a047;
            color: white;
            border: none;
            padding: 14px;
            font-size: 17px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        .btn:hover { background: #388e3c; }

        .detect-btn {
            background: #2196f3;
            margin-bottom: 15px;
        }
        .detect-btn:hover {
            background: #1976d2;
        }

        .location-preview {
            font-size: 15px;
            background: #e3f2fd;
            padding: 12px;
            border-left: 5px solid #2196f3;
            margin: 10px 0 20px;
            display: none;
            border-radius: 5px;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4caf50;
            color: white;
            padding: 14px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.5s ease;
            z-index: 999;
        }
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>



<div class="container">
    <h2>Enter Shipping Address</h2>

    <button type="button" class="btn detect-btn" onclick="detectLocation()">Auto Detect My Location</button>
    <div id="locationPreview" class="location-preview"></div>

    <form action="payment.php" method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <textarea name="address" id="addressField" placeholder="Full Address" rows="4" required></textarea>
        <input type="text" name="pincode" id="pincodeField" placeholder="Pincode" required>
        <input type="text" name="mobile" placeholder="Mobile Number" required pattern="[0-9]{10}" title="Enter 10-digit number">
        <button type="submit" class="btn">Proceed to Payment</button>
    </form>
</div>

<div id="toast" class="toast">📍 Location auto-filled!</div>

<script>
function detectLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, error);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

function success(position) {
    const lat = position.coords.latitude;
    const lon = position.coords.longitude;

    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
        .then(response => response.json())
        .then(data => {
            const address = data.display_name || '';
            const postcode = data.address.postcode || '';
            document.getElementById('addressField').value = address;
            document.getElementById('pincodeField').value = postcode;

            const preview = document.getElementById('locationPreview');
            preview.textContent = "📍 Detected Location: " + address;
            preview.style.display = "block";

            showToast("📍 Location auto-filled!");
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(() => {
            alert("Failed to fetch address. Please enter it manually.");
        });
}

function error() {
    alert("Unable to retrieve your location.");
}

function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}
</script>

</body>
</html>
