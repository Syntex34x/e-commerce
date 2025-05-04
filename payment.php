<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['shipping'] = [
        'name' => $_POST['name'],
        'address' => $_POST['address'],
        'pincode' => $_POST['pincode'],
        'mobile' => $_POST['mobile']
    ];
} else if (empty($_SESSION['shipping'])) {
    header("Location: address.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Payment Method - Hexshop</title>
    <style>
        body {
    font-family: 'Segoe UI', sans-serif;
    background: url('payment.webp') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
}
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: hsla(0, 12.70%, 75.30%, 0.77);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        .payment-option {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .payment-option input {
            margin-right: 10px;
        }
        .btn {
            background-color: #43a047;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #388e3c;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Select Payment Method</h2>
    <div id="error-msg" class="error">Please select a payment method!</div>
    <form id="paymentForm" action="order_success.php" method="post">
        <div class="payment-option">
            <input type="radio" name="payment_method" value="COD" id="cod">
            <label for="cod">Cash on Delivery (COD)</label>
        </div>
        <div class="payment-option">
            <input type="radio" name="payment_method" value="UPI" id="upi">
            <label for="upi">UPI (Google Pay, PhonePe, etc.)</label>
        </div>
        <div class="payment-option">
            <input type="radio" name="payment_method" value="Card" id="card">
            <label for="card">Credit/Debit Card</label>
        </div>
        <button type="submit" class="btn">Confirm and Place Order</button>
    </form>
</div>

<script>
document.getElementById("paymentForm").addEventListener("submit", function(e) {
    const methods = document.getElementsByName("payment_method");
    let selected = false;
    for (let method of methods) {
        if (method.checked) {
            selected = true;
            break;
        }
    }
    if (!selected) {
        e.preventDefault();
        document.getElementById("error-msg").style.display = "block";
    }
});
</script>

</body>
</html>
