<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add more styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .terms-container {
            width: 80%;
            margin: 40px auto;
        }
        .accordion {
            margin-top: 20px;
        }
        .accordion-item {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .accordion-button {
            width: 100%;
            padding: 10px;
            background-color: #f0f0f0;
            border: none;
            border-radius: 5px 5px 0 0;
            cursor: pointer;
        }
        .accordion-button:hover {
            background-color: #ddd;
        }
        .accordion-content {
            padding: 10px;
            border-top: 1px solid #ccc;
            display: none;
        }
        .accordion-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .accordion-content li {
            margin-bottom: 10px;
        }
        .accordion-content li strong {
            font-weight: bold;
        }
        .tabbed-content {
            margin-top: 20px;
        }
        .tab-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px 5px 0 0;
            cursor: pointer;
            background-color: #f0f0f0;
        }
        .tab-button.active {
            background-color: #337ab7;
            color: #fff;
        }
        .tab-content {
            display: none;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<?php include 'header.php'; ?>
<body>
    <div class="terms-container">
        <div class="tabbed-content">
            <button class="tab-button active" onclick="openTab(event, 'customer')">Customer Terms</button>
            <button class="tab-button" onclick="openTab(event, 'shop-owner')">Shop Owner Terms</button>
            <button class="tab-button" onclick="openTab(event, 'delivery')">Delivery Terms</button>
            <button class="tab-button" onclick="openTab(event, 'payment')">Payment Terms</button>
        </div>
        <div id="customer" class="tab-content active">
            <!-- Customer terms content -->
            <h2>Terms and Conditions for Customers</h2>
            <p>Welcome to [Hex shop]! These terms and conditions outline the rules and regulations for the use of our website and services.</p>
            <div class="accordion">
                <!-- Customer terms accordion content -->
                <div class="accordion-item">
                    <button class="accordion-button">General Terms</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Acceptance of Terms</strong>: By using our website, you agree to be bound by these terms and conditions.</li>
                            <li><strong>Changes to Terms</strong>: We reserve the right to modify these terms and conditions at any time without notice.</li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button">Ordering and Payment</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Order Placement</strong>: By placing an order, you agree to pay for the products and services ordered.</li>
                            <li><strong>Payment Methods</strong>: We accept various payment methods, including credit/debit cards and online banking.</li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button">Returns and Refunds</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Return Policy</strong>: We offer a [timeframe] return policy for most products.</li>
                            <li><strong>Refund Process</strong>: Refunds will be processed within [timeframe] of receiving the returned product.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="shop-owner" class="tab-content">
            <!-- Shop owner terms content -->
            <h2>Terms and Conditions for Shop Owners</h2>
            <p>Welcome to [Hex shop]! These terms and conditions outline the rules and regulations for shop owners using our platform.</p>
            <div class="accordion">
                <!-- Shop owner terms accordion content -->
                <div class="accordion-item">
                    <button class="accordion-button">General Terms</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Acceptance of Terms</strong>: By registering as a shop owner on our platform, you agree to be bound by these terms and conditions.</li>
                            <li><strong>Changes to Terms</strong>: We reserve the right to modify these terms and conditions at any time without notice.</li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button">Product Listings</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Product Accuracy</strong>: You agree to ensure that your product listings are accurate and up-to-date.</li>
                            <li><strong>Product Images</strong>: You agree to provide high-quality product images that accurately represent the product.</li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button">Returns and Refunds</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Return Policy</strong>: You agree to have a return policy in place for your products.</li>
                            <li><strong>Refund Process</strong>: You agree to process refunds in a timely manner.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="delivery" class="tab-content">
            <!-- Delivery terms content -->
            <h2>Delivery Terms</h2>
            <p>We strive to deliver products to our customers in a timely and efficient manner.</p>
            <div class="accordion">
                <!-- Delivery terms accordion content -->
                <div class="accordion-item">
                    <button class="accordion-button">Delivery Timeframe</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Delivery Timeframe</strong>: We aim to deliver products within [timeframe] of receiving the order.</li>
                            <li><strong>Delivery Area</strong>: We deliver to [area/city/state].</li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button">Delivery Charges</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Delivery Charges</strong>: Delivery charges may apply, depending on the location and product.</li>
                            <li><strong>Free Delivery</strong>: We offer free delivery on orders over [amount].</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="payment" class="tab-content">
            <!-- Payment terms content -->
            <h2>Payment Terms</h2>
            <p>We accept various payment methods to make it convenient for our customers.</p>
            <div class="accordion">
                <!-- Payment terms accordion content -->
                <div class="accordion-item">
                    <button class="accordion-button">Payment Methods</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Payment Methods</strong>: We accept [list payment methods, e.g. credit/debit cards, online banking, etc.].</li>
                            <li><strong>Payment Security</strong>: We use secure payment gateways to protect your payment information.</li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button">Payment Processing</button>
                    <div class="accordion-content">
                        <ul>
                            <li><strong>Payment Processing</strong>: Payments are processed securely and efficiently.</li>
                            <li><strong>Payment Confirmation</strong>: You will receive a payment confirmation email once your payment is processed.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
<script>
    function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tab-button");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

const accordionButtons = document.querySelectorAll('.accordion-button');

accordionButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const accordionContent = button.nextElementSibling;
        if (accordionContent.style.display === 'block') {
            accordionContent.style.display = 'none';
        } else {
            accordionContent.style.display = 'block';
        }
    });
});</script>

</body>
</html>