<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Category Navigation</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
<style>
   
body {
    margin: 0;
    padding: 0;
}

.category_items {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.sale_item {
    width: 75%;
    margin: 0;
    padding: 0;
}

.sale_item img {
    width: 100%;
    height: auto;
    display: flex;
}
a:-webkit-any-link {
    color: -webkit-link;
    cursor: pointer;
    text-decoration: underline;
}

.category-nav {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    background-color: #f7f7f7;
    padding: 10px;
}

.category-card {
    margin: 10px;
    text-align: center;
}

.category-card img {
    width: 64px;
    height: 64px;
    border-radius: 50%;
}

.category-card span {
    display: block;
    margin-top: 10px;
}

.banner_container {
    width: 100%;
    overflow: hidden;
}

.slider-wrapper {
    width: 100%;
    position: relative;
}

.slider-track {
    display: flex;
    width: 300%;
    animation: slideBanner 12s infinite ease-in-out;
}

.banner_image {
    width: 33.33%;
    height: auto;
    flex-shrink: 0;
    object-fit: cover;
}

@keyframes slideBanner {
    0%, 25% {
        transform: translateX(0%);
    }
    33%, 58% {
        transform: translateX(-33.33%);
    }
    66%, 91% {
        transform: translateX(-66.66%);
    }
    100% {
        transform: translateX(-66.66%);
    }
}

.category_heading {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin: 20px 0;
}

.category_items {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.sale_item {
    width: 100%;
    margin: 0px;
}

@media (max-width: 768px) {
    .sale_item {
        width: 50%;
    }
}

@media (max-width: 480px) {
    .sale_item {
        width: 100%;
    }
}

.footer_container {
    background-color: #333;
    color: #fff;
    padding: 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.footer_column {
    margin: 20px;
}

.footer_column h3 {
    margin-bottom: 10px;
}

.footer_column a {
    color: #fff;
    text-decoration: none;
    display: block;
    margin-bottom: 10px;
}

.copyright {
    text-align: center;
    padding: 10px;
    background-color: #333;
    color: #fff;
}

#chatbot-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #337ab7;
    color: #fff;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
}

#chatbot-icon i {
    font-size: 36px; /* Change the size to 36px */
}

#chatbox {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 300px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    display: none;
}

#chatbox-header {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px;
    border-radius: 10px 10px 0 0;
    cursor: pointer;
}

#chatbox-content {
    padding: 10px;
    height: 300px;
    overflow-y: auto;
}

.message {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 10px;
}

.user-message {
    background-color: #d9fdd3;
    text-align: right;
    margin-left: auto;
}

.bot-message {
    background-color: #f1f1f1;
    text-align: left;
    margin-right: auto;
}

#input {
    width: calc(100% - 20px);
    padding: 10px;
    margin: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

</style>
  <div class="category-nav">
    <div class="category-card" aria-label="Grocery">
    <a href="grocery.php" style="text-decoration: none; color: inherit;">
      <img src="https://rukminim2.flixcart.com/flap/128/128/image/29327f40e9c4d26b.png?q=100" alt="Kilos">
      <span>Kilos</span>
    </div>



    <div class="category-card" aria-label="Fashion">
    <a href="kids.php" style="text-decoration: none; color: inherit;">
      <img src="https://rukminim2.flixcart.com/fk-p-flap/128/128/image/0d75b34f7d8fbcb3.png?q=100" alt="Fashion">
      <span>Fashion</span>
    </div>

    <div class="category-card" aria-label="Electronics">
     <a href="electrical.php" style="text-decoration: none; color: inherit;">
      <img src="https://rukminim2.flixcart.com/flap/128/128/image/69c6589653afdb9a.png?q=100" alt="Electronics">
      <span>Electronics</span>
    </div>

    <div class="category-card" aria-label="Home & Furniture">
    <a href="restaurants.php" style="text-decoration: none; color: inherit;">
      <img src="https://tse2.mm.bing.net/th?id=OIP.e_2icgUx749sEI0czgr7NwHaHa&pid=Api&P=0&h=180?q=100" alt="Home & Furniture">
      <span>food corner</span>
    </div>
 <div class="category-card" aria-label="Home & Furniture">
    <a href="medicine.php" style="text-decoration: none; color: inherit;">
      <img src="https://tse1.mm.bing.net/th?id=OIP.IkfUjtfvKE0NFSPKjqyH5gHaH0&pid=Api&P=0&h=180?q=100" alt="Appliances">
      <span>Medicine</span>
    </div>

  
  </div>

</body>
</html>


  
</head>
<body>

<main>
   <div class="banner_container">
    <div class="slider-wrapper">
        <div class="slider-track">
            <img class="banner_image" src="banner.jpg" alt="Banner 1">
            <img class="banner_image" src="banner1.jpg" alt="Banner 2">
            <img class="banner_image" src="banner2.jpg" alt="Banner 3">
        </div>
    </div>
</div>




    <div class="category_heading">MEDAL WORTHY BRANDS TO BAG</div>
    <div class="category_items">
        <a href="product.php"><img class="sale_item" src="offer/1.png"></a>
        <a href="index.php"><img class="sale_item" src="offer/3.png"></a>
        <a href="grocery.php"><img class="sale_item" src="offer/5.png"></a>
        <a href="kids.php"><img class="sale_item" src="offer/6.png"></a>
        <a href="index.php"><img class="sale_item" src="offer/7.png"></a>
        <a href="index.php"><img class="sale_item" src="offer/8.png"></a>
        <a href="index.php"><img class="sale_item" src="offer/9.png"></a>
 
      
    </div>

    <div class="category_heading">SHOP BY CATEGORY</div>
    <div class="category_items">
        <a href="index.php"><img class="sale_item" src="category/2.jpg"></a>
        <a href="index.php"><img class="sale_item" src="category/3.jpg"></a>
        <a href="restaurants.php"><img class="sale_item" src="category/4.jpg"></a>
        <a href="index.php"><img class="sale_item" src="category/5.jpg"></a>
        <a href="index.php"><img class="sale_item" src="category/6.jpg"></a>
        <a href="index.php"><img class="sale_item" src="category/7.jpg"></a>
        <a href="index.php"><img class="sale_item" src="category/8.jpg"></a>
       
        
    </div>

    <div id="product-container" class="category_items"></div>
</main>

<footer>
    <div class="footer_container">
        <div class="footer_column">
            <h3>ONLINE SHOPPING</h3>
            
            <a href="kids.php">Kids</a>
            <a href="restaurants.php">Food corner</a>
            <a href="medicine.php">Medicine</a>
            <a href="electrical.php">Electronics</a>
            <a href="grocery.php">Grocery</a>
           
          
        </div>
        <div class="footer_column">
            <h3>USEFUL LINKS</h3>
           
            <a href="faq.php">FAQs</a>
            <a href="contact.php">Contact Us</a>
            <a href="term.php">Terms</a>
        </div>
        <div class="footer_column">
            <h3>POLICIES</h3>
            <a href="policy.php">Return Policy</a>
            <a href="security.php">Security</a>
            <a href="mens.php">Privacy</a>
        </div>
        <div class="footer_column">
            <h3>JOIN US</h3>
            <a href="become_seller.php">Become a Seller</a>
            <a href="become_delivery_partner.php">Become a Delivery Partner</a>
        </div>
    </div>
    <hr>
    <div class="copyright">
        © 2025 www.hexshop.com. All rights reserved.
    </div>
</footer>

<!-- Chatbot -->
<div id="chatbot-icon">
    <i class="fas fa-comments"></i> <!-- FontAwesome Chat Icon -->
</div>

<div class="container" id="chatbox">
    <div id="chatbox-header">Chat with Us</div>
    <div id="chatbox-content"></div>
    <input type="text" id="input" placeholder="Type a message..." onkeydown="sendMessage(event)">
</div>

<!-- Chatbot -->
<div id="chatbot-icon">
    <i class="fas fa-comments"></i> <!-- FontAwesome Chat Icon -->
</div>

<div class="container" id="chatbox">
    <div id="chatbox-header">Chat with Us</div>
    <div id="chatbox-content"></div>
    <input type="text" id="input" placeholder="Type a message..." onkeydown="sendMessage(event)">
</div>

<!-- Chatbot -->
<div id="chatbot-icon" onclick="toggleChatbox()">
    <i class="fas fa-comments"></i> <!-- FontAwesome Chat Icon -->
</div>

<div class="container" id="chatbox" style="display: none;">
    <div id="chatbox-header">Chat with Us</div>
    <div id="chatbox-content"></div>
    <input type="text" id="input" placeholder="Type a message..." onkeydown="sendMessage(event)">
</div>

<!-- Chatbot -->
<div id="chatbot-icon" onclick="toggleChatbox()">
    <i class="fas fa-comments"></i> <!-- FontAwesome Chat Icon -->
</div>

<div class="container" id="chatbox" style="display: none;">
    <div id="chatbox-header">Chat with Us</div>
    <div id="chatbox-content"></div>
    <input type="text" id="input" placeholder="Type a message..." onkeydown="sendMessage(event)">
</div>

<script>
    let stage = 0;
    let orders = [];
    let userInput = {};
    let userProfile;
    let complaints = {};
    let complaintId = 0;

    function scrollChat() {
        const chatboxContent = document.getElementById('chatbox-content');
        chatboxContent.scrollTop = chatboxContent.scrollHeight;
    }

    function loadOrders() {
        fetch('orders.json')
        .then(response => response.json())
        .then(data => {
            orders = data;
        })
        .catch(error => console.error('Error loading orders:', error));
    }

    loadOrders();

    function sendMessage(event) {
        if (event.key === 'Enter') {
            const input = document.getElementById('input');
            const message = input.value.trim();
            if (message !== "") {
                displayMessage(message, 'user');
                input.value = '';
                handleMessage(message);
            }
        }
    }

    function displayMessage(message, sender) {
        const chatboxContent = document.getElementById('chatbox-content');
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.classList.add(sender === 'user' ? 'user-message' : 'bot-message');
        messageDiv.textContent = message;
        chatboxContent.appendChild(messageDiv);
        scrollChat();
    }

    function toggleChatbox() {
        const chatbox = document.getElementById('chatbox');
        if (chatbox.style.display === 'block') {
            chatbox.style.display = 'none';
        } else {
            chatbox.style.display = 'block';
            resetConversation();
        }
    }

    function resetConversation() {
        stage = 0;
        userInput = {};
        userProfile = null;
        document.getElementById('chatbox-content').innerHTML = '';
        displayMessage("Hi! Welcome to HexShop. How can I assist you today?", 'bot');
        stage = 1;
    }

    function generateComplaintId() {
        complaintId++;
        return `CMP${complaintId.toString().padStart(5, '0')}`;
    }

    function handleMessage(message) {
        switch (stage) {
            case 0:
                if (message.toLowerCase().includes("hi") || message.toLowerCase().includes("hello")) {
                    displayMessage("Hi! Welcome to HexShop. How can I assist you today?", 'bot');
                    stage = 1;
                } else {
                    displayMessage("Sorry, I didn't understand your query. Please type 'Hi' to start the conversation.", 'bot');
                }
                break;
            case 1:
                if (message.toLowerCase().includes("order")) {
                    displayMessage("Please enter your order ID:", 'bot');
                    stage = 2;
                } else if (message.toLowerCase().includes("complaint")) {
                    displayMessage("Sorry to hear that. Please describe your issue:", 'bot');
                    stage = 5;
                } else if (message.toLowerCase().includes("product")) {
                    displayMessage("We have a wide range of products. Please specify what you're looking for:", 'bot');
                    stage = 9;
                } else {
                    displayMessage("You can type 'Order' to track your order, 'Complaint' to report an issue, or 'Product' to inquire about our products.", 'bot');
                }
                break;
           case 2:
    userInput.orderId = message;
    fetch('orders.json')
        .then(response => response.json())
        .then(data => {
            userProfile = data.find(order => order.id === userInput.orderId);
            if (userProfile) {
                displayMessage(`You have purchased ${userProfile.products.join(', ')}.`, 'bot');
                displayMessage("What would you like to do next?", 'bot');
                displayMessage("1. Track the Order", 'bot');
                displayMessage("2. Report an issue", 'bot');
                displayMessage("3. Ask about product", 'bot');
                stage = 3;
            } else {
                displayMessage("Sorry, we couldn't find your order. Please check the ID and try again.", 'bot');
                stage = 2;
            }
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            displayMessage("There was an error retrieving your order. Please try again later.", 'bot');
            stage = 2;
        });
    break;

            case 3:
                if (message === "1") {
                    displayMessage(`Your order ${userInput.orderId} is ${userProfile.status}.`, 'bot');
                    displayMessage("Do you want to go back to the main menu?", 'bot');
                    stage = 4;
                } else if (message === "2") {
                    displayMessage("Sorry to hear that. Please describe your issue:", 'bot');
                    stage = 5;
                } else if (message === "3") {
                    displayMessage(`You can ask about ${userProfile.products.join(', ')}.`, 'bot');
                    stage = 10;
                } else {
                    displayMessage("Invalid option. Please choose a valid option.", 'bot');
                    stage = 3;
                }
                break;
            case 4:
                if (message.toLowerCase().includes("yes")) {
                    resetConversation();
                } else {
                    displayMessage("Thank you for chatting with us. Goodbye!", 'bot');
                }
                break;
            case 5:
                let complaintNumber = generateComplaintId();
                complaints[complaintNumber] = {
                    issue: message,
                    status: "Open"
                };
                displayMessage(`Your complaint has been registered with complaint number ${complaintNumber}.`, 'bot');
                displayMessage("You can track your complaint using this number.", 'bot');
                displayMessage("Do you want to track your complaint or go back to the main menu?", 'bot');
                stage = 6;
                break;
            case 6:
                if (message.toLowerCase().includes("track")) {
                    displayMessage("Please enter your complaint number:", 'bot');
                    stage = 7;
                } else if (message.toLowerCase().includes("main menu")) {
                    resetConversation();
                } else {
                    displayMessage("Invalid option. Please choose a valid option.", 'bot');
                    stage = 6;
                }
                break;
            case 7:
                let complaintStatus = complaints[message];
                if (complaintStatus) {
                    displayMessage(`Your complaint ${message} is ${complaintStatus.status}.`, 'bot');
                    displayMessage("Do you want to go back to the main menu?", 'bot');
                    stage = 8;
                } else {
                    displayMessage("Invalid complaint number. Please try again.", 'bot');
                    stage = 7;
                }
                break;
            case 8:
                if (message.toLowerCase().includes("yes")) {
                    resetConversation();
                } else {
                    displayMessage("Thank you for chatting with us. Goodbye!", 'bot');
                }
                break;
            case 9:
                displayMessage("We have a wide range of products. Please visit our website for more information.", 'bot');
                displayMessage("Do you want to go back to the main menu?", 'bot');
                stage = 10;
                break;
            case 10:
                if (message.toLowerCase().includes("yes")) {
                    resetConversation();
                } else {
                    displayMessage("Thank you for chatting with us. Goodbye!", 'bot');
                }
                break;
        }
    }
</script>

</body>
</html>
