<?php
session_start();

// Authentication check
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

// CSRF token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// JSON file setup
$usersFile = 'users.json';
$ordersFile = 'orders.json';
$sellersFile = 'seller_requests.json';
$deliveryPartnersFile = 'delivery_partner_requests.json';

$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
$orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];
$sellers = file_exists($sellersFile) ? json_decode(file_get_contents($sellersFile), true) : [];
$deliveryPartners = file_exists($deliveryPartnersFile) ? json_decode(file_get_contents($deliveryPartnersFile), true) : [];

$tab = $_GET['tab'] ?? 'dashboard';
$errors = [];

// Remove user
if ($tab === 'users' && isset($_GET['remove'])) {
    $idToRemove = $_GET['remove'];
    $users = array_filter($users, fn($u) => $u['id'] !== $idToRemove);
    file_put_contents($usersFile, json_encode(array_values($users), JSON_PRETTY_PRINT));
    header("Location: ?tab=users");
    exit;
}

// Create user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("Invalid CSRF token");

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);

    if (empty($username) || empty($password) || empty($name) || empty($email) || empty($mobile)) {
        $errors[] = "All fields are required.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $errors[] = "Username already exists.";
            break;
        }
    }

    if (empty($errors)) {
        $users[] = [
            'id' => uniqid(),
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'created_at' => date('Y-m-d H:i:s')
        ];
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
        $_SESSION['new_user'] = $username;
        header('Location: ?tab=users');
        exit;
    }
}

// Approve seller
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_seller'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("Invalid CSRF token");
    $sellerId = $_POST['seller_id'];

    foreach ($sellers as $index => $seller) {
        if ($seller['id'] === $sellerId) {
            $users[] = [
                'id' => uniqid(),
                'username' => $seller['username'],
                'password' => password_hash('default123', PASSWORD_DEFAULT),
                'role' => 'shop_owner',
                'name' => $seller['name'] ?? '',
                'email' => $seller['email'] ?? '',
                'mobile' => $seller['mobile'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];
            unset($sellers[$index]);
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            file_put_contents($sellersFile, json_encode(array_values($sellers), JSON_PRETTY_PRINT));
            break;
        }
    }
    header('Location: ?tab=sellers');
    exit;
}

// Reject seller
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject_seller'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("Invalid CSRF token");
    $sellerId = $_POST['seller_id'];
    $sellers = array_filter($sellers, fn($s) => $s['id'] !== $sellerId);
    file_put_contents($sellersFile, json_encode(array_values($sellers), JSON_PRETTY_PRINT));
    header('Location: ?tab=sellers');
    exit;
}

// Approve delivery partner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_delivery_partner'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("Invalid CSRF token");
    $partnerId = $_POST['partner_id'];
    foreach ($deliveryPartners as $index => $partner) {
        if ($partner['id'] === $partnerId) {
            $deliveryPartners[$index]['approved'] = true;
            break;
        }
    }
    file_put_contents($deliveryPartnersFile, json_encode(array_values($deliveryPartners), JSON_PRETTY_PRINT));
    header('Location: ?tab=deliveryPartners');
    exit;
}

// Reject delivery partner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject_delivery_partner'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("Invalid CSRF token");
    $partnerId = $_POST['partner_id'];
    $deliveryPartners = array_filter($deliveryPartners, fn($d) => $d['id'] !== $partnerId);
    file_put_contents($deliveryPartnersFile, json_encode(array_values($deliveryPartners), JSON_PRETTY_PRINT));
    header('Location: ?tab=deliveryPartners');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hexshop Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
          body {
    font-family: 'Segoe UI', sans-serif;
    background: url('bg.png') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
}
        header, footer { background: #2c3e50; color: white; text-align: center; padding: 1rem; }
        nav { background: #34495e; display: flex; gap: 1rem; padding: 1rem; }
        nav a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; }
        nav a.active, nav a:hover { background: #1abc9c; }
        .container { padding: 2rem; }
        .section { background: rgba(240, 240, 240, 0.59); padding: 1.5rem; margin-bottom: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        input, select { width: 100%; padding: 0.5rem; }
        button { padding: 0.5rem 1rem; background: #27ae60; color: white; border: none; border-radius: 4px; }
        .user-item { display: flex; justify-content: space-between; padding: 1rem; border-bottom: 1px solid #ccc; }
        .danger { color: red; text-decoration: none; }
        ul { padding: 0; list-style: none; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background-color:rgba(240, 240, 240, 0.59); }
    </style>
</head>
<body>

<header><h1>Hexshop Admin Panel</h1></header>
<nav>
    <a href="?tab=dashboard" class="<?= $tab === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
    <a href="?tab=orders" class="<?= $tab === 'orders' ? 'active' : '' ?>">Orders</a>
    <a href="?tab=users" class="<?= $tab === 'users' ? 'active' : '' ?>">User Management</a>
    <a href="?tab=analytics" class="<?= $tab === 'analytics' ? 'active' : '' ?>">Analytics</a>
    <a href="?tab=sellers" class="<?= $tab === 'sellers' ? 'active' : '' ?>">Seller Requests</a>
    <a href="?tab=deliveryPartners" class="<?= $tab === 'deliveryPartners' ? 'active' : '' ?>">Delivery Partner Requests</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
<?php if ($tab === 'dashboard'): ?>
    <div class="section">
        <h2>Welcome, Admin!</h2>
        <p>Total Users: <?= count($users) ?></p>
        <p>Total Orders: <?= count($orders) ?></p>
    </div>

<?php elseif ($tab === 'orders'): ?>
    <div class="section">
        <h2>Order Management</h2>
        <?php if (empty($orders)): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <table><thead><tr>
                <th>Order ID</th><th>Name</th><th>Mobile</th><th>Products</th>
                <th>Total (₹)</th><th>Date</th><th>Payment</th>
            </tr></thead><tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['user']) ?></td>
                    <td><?= htmlspecialchars($order['shipping']['mobile'] ?? 'N/A') ?></td>
                    <td><ul><?php foreach ($order['products'] as $p) echo "<li>".htmlspecialchars($p)."</li>"; ?></ul></td>
                    <td><?= number_format($order['total'], 2) ?></td>
                    <td><?= date("d M Y, g:i A", strtotime($order['date'])) ?></td>
                    <td><?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></td>
                </tr>
            <?php endforeach; ?></tbody></table>
        <?php endif; ?>
    </div>

<?php elseif ($tab === 'users'): ?>
    <div class="section">
        <button onclick="toggleUserView()" style="float:right;">Switch View</button>
        <h2 id="view-title">Create New User</h2>

        <div id="create-user-view">
            <?php if (!empty($errors)): ?>
                <div style="color:red;"><?php foreach ($errors as $error) echo "<p>$error</p>"; ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="form-group"><label>Username:</label><input type="text" name="username" required></div>
                <div class="form-group"><label>Password:</label><input type="password" name="password" required></div>
                <div class="form-group"><label>Name:</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Email:</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Mobile:</label><input type="text" name="mobile" required></div>
                <div class="form-group"><label>Role:</label><select name="role"><option value="shop_owner">Shop Owner</option></select></div>
                <button type="submit" name="create_user">Create User</button>
            </form>
        </div>

        <div id="existing-users-view" style="display:none;">
            <?php if (empty($users)): ?><p>No users found.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($users as $user): ?>
                        <li class="user-item">
                            <div>
                                <strong><?= htmlspecialchars($user['username']) ?></strong><br>
                                Role: <?= htmlspecialchars($user['role']) ?><br>
                                Name: <?= htmlspecialchars($user['name']) ?><br>
                                Email: <?= htmlspecialchars($user['email']) ?><br>
                                Mobile: <?= htmlspecialchars($user['mobile']) ?><br>
                                Created: <?= $user['created_at'] ?>
                            </div>
                            <a href="?tab=users&remove=<?= $user['id'] ?>" class="danger" onclick="return confirm('Delete user?')">Delete</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

<?php elseif ($tab === 'sellers'): ?>
    <div class="section">
        <h2>Seller Requests</h2>
        <?php if (empty($sellers)): ?><p>No seller requests found.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($sellers as $seller): ?>
                    <li class="user-item">
                        <div>
                            <strong><?= htmlspecialchars($seller['username']) ?></strong><br>
                            Requested at: <?= $seller['created_at'] ?>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="seller_id" value="<?= $seller['id'] ?>">
                            <button type="submit" name="approve_seller">Approve</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="seller_id" value="<?= $seller['id'] ?>">
                            <button type="submit" name="reject_seller">Reject</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

<?php elseif ($tab === 'deliveryPartners'): ?>
    <div class="section">
        <h2>Delivery Partner Requests</h2>
        <?php if (empty($deliveryPartners)): ?><p>No delivery partner requests found.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($deliveryPartners as $partner): ?>
                    <li class="user-item">
                        <div><strong><?= htmlspecialchars($partner['partner_name'] ?? 'N/A') ?></strong><br>
                        Requested at: <?= $partner['created_at'] ?? 'N/A' ?></div>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="partner_id" value="<?= $partner['id'] ?>">
                            <button type="submit" name="approve_delivery_partner">Approve</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="partner_id" value="<?= $partner['id'] ?>">
                            <button type="submit" name="reject_delivery_partner">Reject</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>
</div>

<script>
function toggleUserView() {
    const create = document.getElementById("create-user-view");
    const list = document.getElementById("existing-users-view");
    const title = document.getElementById("view-title");
    if (create.style.display === "none") {
        create.style.display = "block";
        list.style.display = "none";
        title.innerText = "Create New User";
    } else {
        create.style.display = "none";
        list.style.display = "block";
        title.innerText = "Existing Users";
    }
}
</script>

</body>
</html>
