<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Policy</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .return-policy {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .return-policy h2 {
            margin-top: 0;
            color: #337ab7;
        }

        .return-policy ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .return-policy li {
            margin-bottom: 10px;
        }

        .return-policy ol {
            padding: 0;
            margin: 0;
        }

        .return-policy li::before {
            content: "\2022";
            margin-right: 10px;
            color: #337ab7;
        }

        .store-locator {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .store-locator input[type="text"] {
            width: 50%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .store-locator button {
            background-color: #337ab7;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .store-locator button:hover {
            background-color: #23527c;
        }

        .store-results {
            margin-top: 20px;
        }

        .store-results div {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .store-results div:last-child {
            border-bottom: none;
        }

        .accordion {
            margin-top: 20px;
        }

        .accordion .accordion-item {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .accordion .accordion-item .accordion-header {
            padding: 10px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        .accordion .accordion-item .accordion-content {
            padding: 10px;
            display: none;
        }

        .accordion .accordion-item.active .accordion-content {
            display: block;
        }
    </style>
</head>
<?php include 'header.php'; ?>
<body>
    <div class="return-policy">
        <h2>Return Policy</h2>
        <p>We strive to ensure that you are completely satisfied with your purchase. If for any reason you are not satisfied, you can return your item(s) within 2 days of delivery.</p>
        
        <div class="accordion">
            <div class="accordion-item">
                <div class="accordion-header">Eligibility for Return</div>
                <div class="accordion-content">
                    <ul>
                        <li>The item(s) must be in original condition with all tags and packaging intact.</li>
                        <li>The item(s) must not have been worn, used, or altered in any way.</li>
                        <li>The item(s) must be returned with all original accessories and documentation.</li>
                    </ul>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">Return Options</div>
                <div class="accordion-content">
                    <ul>
                        <li><strong>Online Return:</strong> You can return your item(s) online through our website. Simply go to your order history, select the item(s) you want to return, and follow the prompts.</li>
                        <li><strong>Offline Return:</strong> You can also return your item(s) to one of our partner stores. Please visit our store locator to find a store near you.</li>
                    </ul>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">Return Process</div>
                <div class="accordion-content">
                    <ol>
                        <li>Initiate your return online or visit a partner store.</li>
                        <li>Pack the item(s) securely in its original packaging.</li>
                        <li>Ship the item(s) back to us or drop it off at a partner store.</li>
                        <li>Once we receive the item(s), we will process your return and issue a refund.</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <div class="store-locator">
            <h3>Store Locator</h3>
            <input type="text" id="store-search" placeholder="Enter your location">
            <button id="store-search-btn">Search</button>
            <div class="store-results" id="store-results"></div>
        </div>
    </div>

    <script>
        const accordionItems = document.querySelectorAll('.accordion-item');

        accordionItems.forEach((item) => {
            item.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });

        const storeSearchBtn = document.getElementById('store-search-btn');
        const storeSearchInput = document.getElementById('store-search');
        const storeResults = document.getElementById('store-results');

        storeSearchBtn.addEventListener('click', () => {
            const location = storeSearchInput.value.trim();
            if (location) {
                // Call API to get store locations
                // For demonstration purposes, we'll use a mock API response
                const stores = [
                    { name: 'Store 1', address: '123 Main St, Anytown, USA' },
                    { name: 'Store 2', address: '456 Elm St, Othertown, USA' },
                ];
                const storeHtml = stores.map((store) => {
                    return `<div>${store.name} - ${store.address}</div>`;
                }).join('');
                storeResults.innerHTML = storeHtml;
            }
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>