<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

$userFile = 'users.json';
$orderFile = 'orders.json';

// Load user data
if (!file_exists($userFile)) {
    echo "User data file not found!";
    exit;
}
$users = json_decode(file_get_contents($userFile), true);
if (!$users) {
    echo "Invalid user data!";
    exit;
}

// Find the current user from the users array
$currentUser = null;
foreach ($users as $user) {
    if ($user['username'] === $username) {
        $currentUser = $user;
        break;
    }
}

if (!$currentUser) {
    echo "User not found!";
    exit;
}

// Load order data
if (file_exists($orderFile)) {
    $orders = json_decode(file_get_contents($orderFile), true);
    if ($orders === null) {
        $orders = [];
    }
} else {
    $orders = [];
}

// Filter orders that belong to the current user
$userOrders = [];
if (is_array($orders)) {
    foreach ($orders as $order) {
        if (isset($order['user']) && $order['user'] === $username) {
            $userOrders[] = $order;
        }
    }
}

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $newPassword = trim($_POST['new_password']);

    if (!empty($name)) {
        $currentUser['name'] = $name;
    }

    if (!empty($newPassword)) {
        $currentUser['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    if (!empty($_FILES['profile_pic']['tmp_name'])) {
        $profilePicPath = 'uploads/' . bin2hex(random_bytes(16)) . '_' . basename($_FILES['profile_pic']['name']);

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilePicPath)) {
            $currentUser['profile_pic'] = $profilePicPath;
        } else {
            echo "Failed to upload profile picture!";
        }
    }

    // Save the updated user data back to users.json
    foreach ($users as &$user) {
        if ($user['username'] === $username) {
            $user = $currentUser;
            break;
        }
    }

    if (file_put_contents($userFile, json_encode($users, JSON_PRETTY_PRINT)) === false) {
        echo "Failed to save user data!";
    } else {
        header('Location: profile.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .tabs {
            display: flex;
            background-color: #2e86de;
        }

        .tab {
            flex: 1;
            padding: 15px;
            color: white;
            text-align: center;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .tab:hover, .tab.active {
            background-color: #1e6ab5;
        }

        .tab-content {
            display: none;
            padding: 30px;
        }

        .tab-content.active {
            display: block;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            padding: 10px 18px;
            background-color: #2e86de;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #1e6ab5;
        }

        img.profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .logout-btn {
            background-color: #d63031;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #b71c1c;
        }

        .order-item {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .order-item h3 {
            margin: 0;
            color: #333;
        }

        .order-item p {
            margin: 5px 0;
            color: #555;
        }

        .order-item .status {
            font-weight: bold;
        }

        .order-item .status.processing {
            color: #ff9800;
        }

        .order-item .status.shipped {
            color: #2196f3;
        }

        .order-item .status.delivered {
            color: #4caf50;
        }

        .order-date {
            font-style: italic;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="tabs">
        <div class="tab active" data-tab-id="view"> Profile</div>
        <div class="tab" data-tab-id="edit"> Edit</div>
        <div class="tab" data-tab-id="orders"> Orders</div>
    </div>

    <div id="view" class="tab-content active">
        <center>
            <img src="<?= htmlspecialchars($currentUser['profile_pic'] ?? 'default_avatar.png') ?>" id="profilePic" class="profile-picture" alt="Avatar">
            <h2><?= htmlspecialchars($currentUser['name']) ?></h2>
            <p><strong>Username:</strong> <?= htmlspecialchars($currentUser['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($currentUser['role']) ?></p>
        </center>
        <form method="POST" action="logout.php">
            <button type="submit" class="logout-btn"> Logout</button>
        </form>
    </div>

    <div id="edit" class="tab-content">
        <form id="profileForm" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" value="<?= htmlspecialchars($currentUser['name']) ?>" placeholder="Your Name" required>
            <input type="text" value="<?= htmlspecialchars($currentUser['username']) ?>" disabled>
            <input type="password" name="new_password" placeholder="New Password (leave blank to keep current)">
            <input type="file" name="profile_pic" accept="image/*">
            <button type="submit">Save Changes</button>
        </form>
    </div>

    <div id="orders" class="tab-content">
    <h2>Your Orders</h2>
    <?php if (!empty($userOrders)): ?>
        <?php foreach ($userOrders as $order): ?>
            <div class="order-item">
                <h3>Order ID: <?= htmlspecialchars($order['id'] ?? 'Unknown') ?></h3>
                <p><strong>Status:</strong> 
                    <span class="status 
                        <?php 
                            if ($order['status'] == 'Processing') {
                                echo 'processing';
                            } elseif ($order['status'] == 'Shipped') {
                                echo 'shipped';
                            } elseif ($order['status'] == 'Delivered') {
                                echo 'delivered';
                            }
                        ?>
                    ">
                        <?= htmlspecialchars($order['status'] ?? 'Unknown') ?>
                    </span>
                </p>
                <p><strong>Total:</strong> $<?= htmlspecialchars(number_format((float)($order['total'] ?? 0), 2)) ?></p>
                <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method'] ?? 'Unknown') ?></p>
                <p><strong>Delivery Time:</strong> <?= htmlspecialchars($order['delivery'] ?? 'Unknown') ?></p>
                <p><strong>Products:</strong>
                    <ul>
                        <?php foreach ($order['products'] as $product): ?>
                            <li><?= htmlspecialchars($product) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </p>
                <p><strong>Shipping Details:</strong>
                    <ul>
                        <li>Name: <?= htmlspecialchars($order['shipping']['name'] ?? 'Unknown') ?></li>
                        <li>Address: <?= htmlspecialchars($order['shipping']['address'] ?? 'Unknown') ?></li>
                        <li>Pincode: <?= htmlspecialchars($order['shipping']['pincode'] ?? 'Unknown') ?></li>
                        <li>Mobile: <?= htmlspecialchars($order['shipping']['mobile'] ?? 'Unknown') ?></li>
                    </ul>
                </p>
                <?php if (!empty($order['date'])): ?>
                    <p class="order-date"><strong>Order Date:</strong> <?= htmlspecialchars($order['date']) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You haven't placed any orders yet. <a href="#">Start shopping now!</a></p>
    <?php endif; ?>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = tab.getAttribute('data-tab-id');

                    // Remove active class from all tabs and tab contents
                    document.querySelectorAll('.tab, .tab-content').forEach(el => el.classList.remove('active'));

                    // Add active class to the selected tab and tab content
                    tab.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>

</div>
</body>
</html>