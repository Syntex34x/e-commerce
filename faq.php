<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .faq-container {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .faq-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        details {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        summary {
            font-weight: bold;
            cursor: pointer;
        }
        summary:hover {
            color: #337ab7;
        }
        details[open] summary {
            color: #337ab7;
        }
        .contact-link {
            text-align: center;
            margin-top: 20px;
        }
        .contact-link a {
            text-decoration: none;
            color: #337ab7;
        }
        .contact-link a:hover {
            color: #23527c;
        }
        .search-bar {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
    </style>
</head>
<?php include 'header.php'; ?>
<body>
    <div class="faq-container">
        <h2>Frequently Asked Questions</h2>
        <input type="search" id="search-bar" class="search-bar" placeholder="Search FAQs...">
        <details>
            <summary>Q: How do I place an order?</summary>
            <p>A: To place an order, simply browse our website, add the products you wish to purchase to your cart, and follow the checkout process. You will be asked to provide your shipping and payment information.</p>
        </details>
        <details>
            <summary>Q: What payment methods do you accept?</summary>
            <p>A: We accept various payment methods, including credit/debit cards, net banking, and cash on delivery (COD).</p>
        </details>
        <details>
            <summary>Q: How long does shipping take?</summary>
            <p>A: Shipping times vary depending on your location. Typically, orders are delivered within 3-7 business days within India and 7-14 business days for international orders.</p>
        </details>
        <details>
            <summary>Q: Can I track my order?</summary>
            <p>A: Yes, you can track your order using the tracking number provided in your order confirmation email.</p>
        </details>
        <details>
            <summary>Q: What is your return policy?</summary>
            <p>A: We offer a 30-day return policy. If you're not satisfied with your purchase, you can return it within 30 days for a full refund or exchange.</p>
        </details>
        <details>
            <summary>Q: How do I initiate a return?</summary>
            <p>A: To initiate a return, please contact our customer support team with your order number and reason for return. We'll guide you through the process.</p>
        </details>
        <div class="contact-link">
            <p>Still have questions? <a href="contact.php">Contact Us</a></p>
        </div>
    </div>

    <script>
        const searchBar = document.getElementById('search-bar');
        const details = document.querySelectorAll('details');

        searchBar.addEventListener('input', () => {
            const searchQuery = searchBar.value.toLowerCase();
            details.forEach((detail) => {
                const summary = detail.querySelector('summary').textContent.toLowerCase();
                const content = detail.querySelector('p').textContent.toLowerCase();
                if (summary.includes(searchQuery) || content.includes(searchQuery)) {
                    detail.style.display = 'block';
                } else {
                    detail.style.display = 'none';
                }
            });
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>