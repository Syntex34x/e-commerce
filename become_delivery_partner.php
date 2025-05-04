<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    // You can save this info to a JSON or send an email.
    $data = [
        'role' => 'delivery_partner',
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'message' => $message,
        'submitted_at' => date('Y-m-d H:i:s')
    ];
    file_put_contents('delivery_requests.json', json_encode($data, JSON_PRETTY_PRINT), FILE_APPEND);
    $success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Become a Delivery Partner - Hexshop</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f9f9f9; }
        form { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #20c997; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; }
        h2 { text-align: center; margin-bottom: 20px; }
        .success { color: green; text-align: center; }
    </style>
</head>
<body>

<h2>Become a Delivery Partner</h2>
<?php if (!empty($success)) echo "<p class='success'>Thanks for applying! We’ll be in touch soon.</p>"; ?>

<form method="POST">
    <input type="text" name="name" placeholder="Your Name" required />
    <input type="email" name="email" placeholder="Email Address" required />
    <input type="tel" name="phone" placeholder="Phone Number" required />
    <textarea name="message" placeholder="Tell us why you want to deliver with Hexshop..." rows="5" required></textarea>
    <button type="submit">Submit</button>
</form>

</body>
</html>
