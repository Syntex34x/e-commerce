
<?php
session_start();

require 'vendor/autoload.php'; // Load Composer autoloader for Twilio
use Twilio\Rest\Client;

$usersFile = 'users.json';
$ordersFile = 'orders.json';
$productsFile = 'products.json'; // ✅ corrected typo

// --- Utility functions for file operations ---
function saveJsonSafely($file, $data) {
    $tempFile = $file . '.tmp';
    $backup = $file . '.bak';

    if (file_put_contents($tempFile, json_encode($data, JSON_PRETTY_PRINT)) === false) {
        throw new Exception("Failed to write to temporary file");
    }

    if (file_exists($file)) {
        rename($file, $backup);
    }

    if (!rename($tempFile, $file)) {
        if (file_exists($backup)) {
            rename($backup, $file);
        }
        throw new Exception("Failed to save file");
    }

    if (file_exists($backup)) {
        unlink($backup);
    }
}

function loadJsonSafely($file) {
    if (!file_exists($file)) return [];
    $data = json_decode(file_get_contents($file), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON in $file: " . json_last_error_msg());
    }
    return $data;
}

// --- Load data ---
try {
    $users = loadJsonSafely($usersFile);
    $orders = loadJsonSafely($ordersFile);
    $products = loadJsonSafely($productsFile);
} catch (Exception $e) {
    error_log("Error loading data: " . $e->getMessage());
    die("Error loading data. Please try again later.");
}

// --- Handle Login ---
if (!isset($_SESSION['loggedin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        foreach ($users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        }
        $loginError = "Invalid credentials.";
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head><meta charset="UTF-8"><title>Shop Owner Login</title></head>
    <body>
    <h2>Shop Owner Login</h2>
    <?php if (!empty($loginError)) echo "<p style='color:red;'>$loginError</p>"; ?>
    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    </body>
    </html>
    <?php
    exit;
}

// --- Auth Validation ---
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'shop_owner') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$currentUser  = array_values(array_filter($users, fn($u) => $u['username'] === $username && $u['role'] === 'shop_owner'))[0] ?? null;
if (!$currentUser ) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// --- CSRF Token ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// --- Filter Orders ---
$shopOwnerOrders = array_filter($orders, fn($o) => $o['shop_owner'] === $username);

// --- Place Order ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $order = [
        'id' => uniqid(),
        'user' => $_POST['user'],
        'total' => $_POST['total'],
        'date' => date('Y-m-d H:i:s'),
        'shop_owner' => $username,
    ];
    $orders[] = $order;
    try {
        saveJsonSafely($ordersFile, $orders);
    } catch (Exception $e) {
        error_log("Error saving order: " . $e->getMessage());
        die("Order could not be saved.");
    }

    // SMS Notification
    $shopOwner = array_values(array_filter($users, fn($u) => $u['username'] === $username))[0] ?? null;
    if ($shopOwner && !empty($shopOwner['phone'])) {
        $sid = 'YOUR_TWILIO_ACCOUNT_SID';
        $token = 'YOUR_TWILIO_AUTH_TOKEN';
        $twilio = new Client($sid, $token);
        $message = "New order received from {$order['user']}! Total: $ {$order['total']}";
        try {
            $twilio->messages->create($shopOwner['phone'], [
                'from' => 'YOUR_TWILIO_PHONE_NUMBER',
                'body' => $message
            ]);
        } catch (Exception $e) {
            error_log("SMS failed: " . $e->getMessage());
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Order status update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    foreach ($orders as &$order) {
        if ($order['id'] === $orderId && $order['shop_owner'] === $username) {
            $order['status'] = $newStatus;
            break;
        }
    }
    saveJsonSafely($ordersFile, $orders);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// --- Add Product ---
$errors = [];
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $name = trim($_POST['product_name']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $details = trim($_POST['details']);
    $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : null;
    $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;

    if (empty($name) || $price <= 0 || empty($category) || empty($details)) {
        $errors[] = "All fields are required including category and product details.";
    } else {
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imagePath = $uploadDir . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }

        $newProduct = [
            'id' => uniqid(),
            'name' => $name,
            'price' => $price,
            'category' => $category,
            'details' => $details,
            'image' => $imagePath,
            'owner' => $username,
            'created_at' => date('Y-m-d H:i:s'),
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        $products[] = $newProduct;
        try {
            saveJsonSafely($productsFile, $products);
            $categoryFile = strtolower($category) . '.json';
            $categoryProducts = file_exists($categoryFile) ? loadJsonSafely($categoryFile) : [];
            $categoryProducts[] = $newProduct;
            saveJsonSafely($categoryFile, $categoryProducts);
        } catch (Exception $e) {
            error_log("Error saving product: " . $e->getMessage());
            die("Product could not be saved.");
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// --- Delete Product ---
if (isset($_GET['delete_product'])) {
    $products = array_filter($products, fn($p) => $p['id'] !== $_GET['delete_product']);
    try {
        saveJsonSafely($productsFile, array_values($products));
    } catch (Exception $e) {
        error_log("Error deleting product: " . $e->getMessage());
        die("Failed to delete product.");
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Pagination setup ---
$itemsPerPage = 5; // Items per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$totalOrders = count($shopOwnerOrders);
$totalProducts = count($products);
$totalPagesOrders = ceil($totalOrders / $itemsPerPage);
$totalPagesProducts = ceil($totalProducts / $itemsPerPage);

// --- Paginate Orders ---
$offsetOrders = ($page - 1) * $itemsPerPage;
$paginatedOrders = array_slice($shopOwnerOrders, $offsetOrders, $itemsPerPage);

// --- Paginate Products ---
$offsetProducts = ($page - 1) * $itemsPerPage;
$paginatedProducts = array_slice($products, $offsetProducts, $itemsPerPage);

$myProducts = array_filter($products, fn($p) => $p['owner'] === $username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shop Owner Panel | Hexshop</title>
  <style>
    body { font-family: Arial; background: #f2f2f2; margin: 0; }
    header { background: #34495e; color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; }
    nav a { color: white; text-decoration: none; }
    .container { background: white; padding: 2rem; margin: 1rem auto; width: 90%; max-width: 1000px; }
    h2, h3 { color: #2c3e50; }
    .form-group { margin-bottom: 1rem; }
    input, select, textarea { padding: 0.5rem; width: 100%; }
    button { padding: 0.5rem 1rem; background: #2980b9; color: white; border: none; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    th, td { padding: 0.75rem; border: 1px solid #ccc; }
    th { background-color: #ecf0f1; }
    .danger { color: red; text-decoration: none; }
    img { max-width: 100px; }
  </style>
</head>
<body>

<header>
  <h1>Shop Owner Panel - <?= htmlspecialchars($username) ?></h1>
  <nav><a href="logout.php">Logout</a></nav>
</header>

<div class="container">

<h2>My Orders</h2>
<?php if ($paginatedOrders): ?>
    <table>
    <tr><th>ID</th><th>User</th><th>Product</th><th>Image</th><th>Total</th><th>Date</th><th>Status</th><th>Update</th></tr>
    <?php foreach ($paginatedOrders as $order): 
        $product = null;
        foreach ($products as $p) {
            if (isset($order['product_id']) && $p['id'] === $order['product_id']) {
                $product = $p;
                break;
            }
        }
    ?>
        <tr>
            <td><?= htmlspecialchars($order['id']) ?></td>
            <td><?= htmlspecialchars($order['user']) ?></td>
            <?php if ($product): ?>
                <td><?= htmlspecialchars($product['products']) ?></td>
                <td>
                    <?php if (isset($product['image']) && file_exists($product['image'])): ?>
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 50px; height: 50px;">
                    <?php else: ?>
                        <img src="default-image.jpg" alt="Default Image" style="width: 50px; height: 50px;">
                    <?php endif; ?>
                </td>
            <?php else: ?>
                <td>Product not found</td>
                <td>N/A</td>
            <?php endif; ?>
            <td>$<?= htmlspecialchars($order['total']) ?></td>
            <td><?= htmlspecialchars($order['date']) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <select name="status">
                        <option <?= $order['status'] === 'Processing' ? 'selected' : '' ?>>Processing</option>
                        <option <?= $order['status'] === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option <?= $order['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                        <option <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status">Update</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p>No orders yet.</p>
  <?php endif; ?>

  <h2>Manage My Products</h2>
  <?php if (!empty($errors)): ?>
    <div style="color: red;">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">

    <div class="form-group">
      <label>Product Name:</label>
      <input type="text" name="product_name" required>
    </div>
    <div class="form-group">
      <label> Category:</label>
      <select name="category" required>
        <option value="">-- Select Category --</option>
        <option>Men</option>
        <option>Kids</option>
        <option>Grocery</option>
        <option>Electrical</option>
        <option>Medicine</option>
        <option>Restaurants</option>
      </select>
    </div>
    <div class="form-group">
      <label>Price:</label>
      <input type="number" step="0.01" name="price" required>
    </div>
    <div class="form-group">
      <label>Product Details:</label>
      <textarea name="details" required></textarea>
    </div>
    <div class="form-group">
      <label>Product Image:</label>
      <input type="file" name="image" accept="image/*">
    </div>
    <button type="submit" name="add_product">Add Product</button>
  </form>

  <h3>My Product List</h3>
  <?php if ($myProducts): ?>
    <table>
      <tr><th>Image</th><th>Name</th><th>Price</th><th>Category</th><th>Details</th><th>Location</th><th>Created</th><th>Action</th></tr>
      <?php foreach ($myProducts as $product): ?>
        <tr>
          <td><?php if ($product['image']) echo "<img src='" . htmlspecialchars($product['image']) . "' alt='Product'>"; ?></td>
          <td><?= htmlspecialchars($product['name']) ?></td>
          <td>$<?= htmlspecialchars($product['price']) ?></td>
          <td><?= htmlspecialchars($product['category']) ?></td>
          <td><?= htmlspecialchars($product['details']) ?></td>
          <td>
            <?= isset($product['latitude']) && isset($product['longitude']) ? "Lat: {$product['latitude']}<br>Lng: {$product['longitude']}" : 'N/A' ?>
          </td>
          <td><?= htmlspecialchars($product['created_at']) ?></td>
          <td><a class="danger" href="?delete_product=<?= $product['id'] ?>">Delete</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>You have not added any products.</p>
  <?php endif; ?>
</div>

<script>
  window.onload = function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
      }, function(error) {
        console.warn("Geolocation error:", error.message);
      });
    } else {
      console.warn("Geolocation not supported.");
    }
  };
</script>

</body>
</html>
```