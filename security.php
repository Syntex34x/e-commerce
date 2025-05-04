<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
</head>
<?php include 'header.php'; ?>
<body>

<!-- Security Section -->
<style>
body {
    font-family: Arial, sans-serif;
}

.security-container {
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
</style>
    <div class="security-container">
        <h2>Protecting Your Information</h2>
        <p>At [Hex shop], we take the security of your personal and payment information seriously. We use industry-standard encryption and security measures to protect your data and ensure a safe shopping experience.</p>
        
        <div class="accordion">
            <div class="accordion-item">
                <button class="accordion-button">Security Measures</button>
                <div class="accordion-content">
                    <ul>
                        <li><strong>SSL Encryption</strong>: Our website uses SSL (Secure Sockets Layer) encryption to protect your data as it is transmitted between your browser and our servers.</li>
                        <li><strong>Secure Payment Gateway</strong>: We use a secure payment gateway that is compliant with industry standards for payment card security.</li>
                        <li><strong>Data Protection</strong>: We take reasonable steps to protect your personal and payment information from unauthorized access, disclosure, or theft.</li>
                        <li><strong>Regular Security Updates</strong>: We regularly update our security measures to stay ahead of potential threats and vulnerabilities.</li>
                    </ul>
                </div>
            </div>
            <div class="accordion-item">
                <button class="accordion-button">Your Role in Security</button>
                <div class="accordion-content">
                    <ul>
                        <li><strong>Use Strong Passwords</strong>: Use strong and unique passwords for your account, and avoid using the same password across multiple sites.</li>
                        <li><strong>Keep Your Account Information Up-to-Date</strong>: Make sure to keep your account information, including your email address and password, up-to-date.</li>
                        <li><strong>Be Cautious of Phishing Scams</strong>: Be cautious of phishing scams that may attempt to trick you into revealing your personal or payment information.</li>
                    </ul>
                </div>
            </div>
            <div class="accordion-item">
                <button class="accordion-button">What We Do in Case of a Security Breach</button>
                <div class="accordion-content">
                    <ul>
                        <li><strong>Notification</strong>: In the event of a security breach, we will notify you promptly and provide you with information on what you can do to protect yourself.</li>
                        <li><strong>Investigation</strong>: We will investigate the breach and take reasonable steps to prevent similar breaches from occurring in the future.</li>
                        <li><strong>Cooperation with Authorities</strong>: We will cooperate with law enforcement and other authorities to investigate and prosecute any individuals or organizations responsible for the breach.</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <p>If you have any questions or concerns about our security measures or policies, please contact us at <a href="mailto:support@ecommercewebsite.com">support@ecommercewebsite.com</a>.</p>
    </div>
    <?php include 'footer.php'; ?>

    <script> const accordionButtons = document.querySelectorAll('.accordion-button');

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