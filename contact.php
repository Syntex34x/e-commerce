<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .tabbed-content {
            width: 80%;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            border-bottom: 1px solid #ccc;
        }

        .tab-button {
            background-color: #f9f9f9;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px 5px 0 0;
        }

        .tab-button.active {
            background-color: #337ab7;
            color: #fff;
        }

        .tab-content {
            display: none;
            padding: 20px;
        }

        .tab-content.active {
            display: block;
        }

        #contact-form {
            margin-top: 20px;
        }

        #contact-form label {
            display: block;
            margin-bottom: 10px;
        }

        #contact-form input, #contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }

        #contact-form input[type="submit"] {
            background-color: #337ab7;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #contact-form input[type="submit"]:hover {
            background-color: #23527c;
        }

        #form-response {
            margin-top: 20px;
        }
    </style>
</head>
<?php include 'header.php'; ?>
<body>
    <div class="tabbed-content">
        <div class="tabs">
            <button class="tab-button active" data-tab-target="#contact-info">Contact Info</button>
            <button class="tab-button" data-tab-target="#contact-form-tab">Contact Form</button>
            <button class="tab-button" data-tab-target="#office-hours">Office Hours</button>
        </div>
        <div id="contact-info" class="tab-content active">
            <h3>Contact Information</h3>
            <p><strong>Phone:</strong> +91 12345 67890 (Monday to Saturday, 10am to 6pm IST)</p>
            <p><strong>Email:</strong> <a href="mailto:support@ecommercewebsite.com">support@ecommercewebsite.com</a></p>
            <p><strong>Address:</strong> E-commerce Website, 123, Main Street, Anytown, India 123456</p>
        </div>
        <div id="contact-form-tab" class="tab-content">
            <h3>Contact Form</h3>
            <form id="contact-form" action="indexs.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone">
                
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>
                
                <input type="submit" value="Send Message">
            </form>
            <div id="form-response"></div>
        </div>
        <div id="office-hours" class="tab-content">
            <h3>Office Hours</h3>
            <p>Monday to Saturday: 10am to 6pm IST</p>
            <p>Sunday: Closed</p>
        </div>
    </div>

    <script>
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const tabTarget = button.getAttribute('data-tab-target');
                tabButtons.forEach((btn) => btn.classList.remove('active'));
                tabContents.forEach((content) => content.classList.remove('active'));
                button.classList.add('active');
                document.querySelector(tabTarget).classList.add('active');
            });
        });

        const form = document.getElementById('contact-form');
        const formResponse = document.getElementById('form-response');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(form.action, {
                method: form.method,
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                formResponse.innerText = data.message;
                form.reset();
            })
            .catch((error) => {
                formResponse.innerText = 'Error submitting form. Please try again.';
            });
        });
    </script>
    

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];

        $enquiryData = json_decode(file_get_contents('response.json'), true) ?: [];
        $enquiryData[] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
        ];
        file_put_contents('response.json', json_encode($enquiryData, JSON_PRETTY_PRINT));

        header('Content-Type: application/json');
        echo json_encode(['message' => 'Form submitted successfully!']);
        exit;
    }
    include 'footer.php';
    ?>
</body>

</html>