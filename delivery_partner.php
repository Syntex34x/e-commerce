<?php
$orders = json_decode(file_get_contents('orders.json'), true);

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    foreach ($orders as &$order) {
        if ($order['id'] == $order_id) {
            $order['status'] = $new_status;
            break;
        }
    }

    file_put_contents('orders.json', json_encode($orders));
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delivery location update
if (isset($_POST['update_location'])) {
    $order_id = $_POST['order_id'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    foreach ($orders as &$order) {
        if ($order['id'] == $order_id) {
            if (!isset($order['delivery_location'])) {
                $order['delivery_location'] = [];
            }
            $order['delivery_location'] = [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            break;
        }
    }

    file_put_contents('orders.json', json_encode($orders));
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Search specific order by ID
$found_order = null;
if (isset($_GET['order_id'])) {
    foreach ($orders as $order) {
        if ($order['id'] == $_GET['order_id']) {
            $found_order = $order;
            break;
        }
    }
}

// Categorized Orders
$categories = [
    'Total' => $orders,
    'Assigned' => array_filter($orders, fn($o) => $o['status'] === 'Shipped'),
    'OutForDelivery' => array_filter($orders, fn($o) => $o['status'] === 'Out for Delivery'),
    'Delivered' => array_filter($orders, fn($o) => $o['status'] === 'Delivered'),
    'Cancelled' => array_filter($orders, fn($o) => $o['status'] === 'Cancelled'),
];

// Get current location (would be replaced with actual tracking in production)
function getRandomLocation($base_lat = 28.6139, $base_lng = 77.2090) {
    // Random offset around Delhi (for demo purposes)
    $lat_offset = (mt_rand(-1000, 1000) / 10000);
    $lng_offset = (mt_rand(-1000, 1000) / 10000);
    return [
        'latitude' => $base_lat + $lat_offset,
        'longitude' => $base_lng + $lng_offset
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f0f2f5; }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .tab {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .tab button {
            flex: 1;
            padding: 12px;
            background: none;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            min-width: 100px;
        }
        .tab button:hover, .tab button.active {
            background-color: #007bff;
            color: white;
        }
        .tabcontent {
            display: none;
            animation: fadeIn 0.5s;
        }
        .tabcontent.active {
            display: block;
        }
        .order-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .order-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .order-card h4 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .order-card p {
            margin: 5px 0;
        }
        button.action-btn {
            background: #007bff;
            border: none;
            padding: 8px 14px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button.action-btn:hover {
            background: #0056b3;
        }
        .contact-btn {
            background: #28a745;
            margin-right: 5px;
        }
        .map-btn {
            background: #17a2b8;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            margin-top: 10px;
        }
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        select, input[type="text"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        #map-container {
            height: 400px;
            margin: 20px 0;
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: relative;
        }
        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .contact-modal-body {
            margin-top: 20px;
        }
        .contact-option {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            text-decoration: none;
            color: #212529;
            transition: background-color 0.2s;
        }
        .contact-option:hover {
            background: #e9ecef;
        }
        .contact-option i {
            width: 30px;
            font-size: 20px;
            color: #007bff;
        }
        .location-form input {
            width: calc(50% - 5px);
            display: inline-block;
        }
        .location-inputs {
            display: flex;
            gap: 10px;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .refresh-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
            color: white;
        }
        .status-shipped { background-color: #ffc107; color: #212529; }
        .status-out { background-color: #0dcaf0; }
        .status-delivered { background-color: #198754; }
        .status-cancelled { background-color: #dc3545; }
    </style>
</head>
<body>
<div class="container">
    <div class="dashboard-header">
        <h2>Order Management Dashboard</h2>
        <button class="refresh-btn" onclick="window.location.reload();">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
    
    <div class="tab">
        <?php foreach ($categories as $name => $data): ?>
            <button class="tablinks" onclick="openTab(event, '<?= $name ?>')"><?= $name ?> (<?= count($data) ?>)</button>
        <?php endforeach; ?>
    </div>

    <?php foreach ($categories as $name => $data): ?>
        <div id="<?= $name ?>" class="tabcontent">
            <?php if (empty($data)): ?>
                <p>No orders found in this category.</p>
            <?php else: ?>
                <?php foreach ($data as $order): ?>
                    <div class="order-card">
                        <h4>Order #<?= $order['id'] ?></h4>
                        <p><strong>Customer:</strong> <?= $order['shipping']['name'] ?></p>
                        <p>
                            <strong>Status:</strong> 
                            <span class="status-badge status-<?= strtolower(str_replace(' ', '', $order['status'])) ?>">
                                <?= $order['status'] ?>
                            </span>
                        </p>
                        <p><strong>Total:</strong> ₹<?= $order['total'] ?></p>
                        
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status">
                                <option value="Shipped" <?= $order['status'] === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="Out for Delivery" <?= $order['status'] === 'Out for Delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                                <option value="Delivered" <?= $order['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button class="action-btn" type="submit" name="update_status">Update Status</button>
                        </form>
                        
                        <div class="action-buttons">
                            <button class="action-btn contact-btn" onclick="openContactModal('<?= $order['id'] ?>', '<?= $order['shipping']['name'] ?>', '<?= $order['shipping']['mobile'] ?>')">
                                <i class="fas fa-phone"></i> Contact
                            </button>
                            <button class="action-btn map-btn" onclick="showOnMap('<?= $order['id'] ?>', '<?= $order['shipping']['name'] ?>', <?= isset($order['delivery_location']) ? $order['delivery_location']['latitude'] : 'null' ?>, <?= isset($order['delivery_location']) ? $order['delivery_location']['longitude'] : 'null' ?>)">
                                <i class="fas fa-map-marker-alt"></i> Track
                            </button>
                            <a href="?order_id=<?= $order['id'] ?>" class="action-btn" style="text-decoration: none; display: inline-block; background: #6c757d;">
                                <i class="fas fa-info-circle"></i> Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div id="map-container">
        <h3 id="map-title">Order Tracking</h3>
        <div id="map" style="height: 350px;"></div>
        
        <!-- Location Update Form (for delivery personnel) -->
        <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
            <h4>Update Delivery Location</h4>
            <form method="post" class="location-form">
                <input type="hidden" id="update-order-id" name="order_id" value="">
                <div class="location-inputs">
                    <input type="text" name="latitude" id="update-lat" placeholder="Latitude">
                    <input type="text" name="longitude" id="update-lng" placeholder="Longitude">
                </div>
                <button type="submit" name="update_location" class="action-btn" style="margin-top: 10px; width: 100%;">
                    <i class="fas fa-location-arrow"></i> Update Location
                </button>
            </form>
            <button onclick="useCurrentLocation()" class="action-btn" style="margin-top: 10px; width: 100%; background: #6c757d;">
                <i class="fas fa-crosshairs"></i> Use Current Location
            </button>
        </div>
    </div>

    <hr style="margin: 30px 0;">
    <div id="search-order">
        <h3>Search Order</h3>
        <form method="get">
            <input type="text" name="order_id" placeholder="Enter Order ID">
            <button type="submit" class="action-btn">Search</button>
        </form>
        <p style="text-align:center;">OR</p>
        <button onclick="startScanner()" class="action-btn" style="width:100%;">
            <i class="fas fa-qrcode"></i> Scan QR Code
        </button>
        <div id="scanner-container" style="display:none;">
            <video id="preview" style="width:100%; height:300px; margin-top:10px; border-radius: 6px;"></video>
        </div>
    </div>

    <?php if ($found_order): ?>
        <hr>
        <div class="order-details">
            <h3>Order Details: #<?= $found_order['id'] ?></h3>
            <p><strong>Customer:</strong> <?= $found_order['shipping']['name'] ?></p>
            <p><strong>Address:</strong> <?= $found_order['shipping']['address'] ?>, <?= $found_order['shipping']['pincode'] ?></p>
            <p><strong>Phone:</strong> <a href="tel:<?= $found_order['shipping']['mobile'] ?>"><?= $found_order['shipping']['mobile'] ?></a></p>
            <p><strong>Products:</strong> <?= implode(', ', $found_order['products']) ?></p>
            <p>
                <strong>Status:</strong> 
                <span class="status-badge status-<?= strtolower(str_replace(' ', '', $found_order['status'])) ?>">
                    <?= $found_order['status'] ?>
                </span>
            </p>
            <p><strong>Total:</strong> ₹<?= $found_order['total'] ?></p>
            
            <div class="action-buttons">
                <button class="action-btn contact-btn" onclick="openContactModal('<?= $found_order['id'] ?>', '<?= $found_order['shipping']['name'] ?>', '<?= $found_order['shipping']['mobile'] ?>')">
                    <i class="fas fa-phone"></i> Contact Customer
                </button>
                <button class="action-btn map-btn" onclick="showOnMap('<?= $found_order['id'] ?>', '<?= $found_order['shipping']['name'] ?>', <?= isset($found_order['delivery_location']) ? $found_order['delivery_location']['latitude'] : 'null' ?>, <?= isset($found_order['delivery_location']) ? $found_order['delivery_location']['longitude'] : 'null' ?>)">
                    <i class="fas fa-map-marker-alt"></i> Track Delivery
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Contact Modal -->
<div id="contactModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeContactModal()">&times;</span>
        <h3>Contact Customer</h3>
        <p id="customer-name">Name: </p>
        <div class="contact-modal-body">
            <a href="#" id="call-customer" class="contact-option">
                <i class="fas fa-phone"></i> Call Customer
            </a>
            <a href="#" id="whatsapp-customer" class="contact-option">
                <i class="fab fa-whatsapp"></i> WhatsApp Message
            </a>
            <a href="#" id="sms-customer" class="contact-option">
                <i class="fas fa-sms"></i> Send SMS
            </a>
        </div>
    </div>
</div>

<!-- JS for Tabs, Scanner, Map & Contact -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script>
    // Tab functionality
    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    // QR Scanner
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    scanner.addListener('scan', function (content) {
        window.location.href = '?order_id=' + content;
    });

    function startScanner() {
        document.getElementById('scanner-container').style.display = 'block';
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                alert('No camera found!');
            }
        }).catch(console.error);
    }

    // Map functionality
    let map;
    let marker;
    
    function initMap() {
        map = L.map('map').setView([28.6139, 77.2090], 12); // Default to Delhi
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    }

    function showOnMap(orderId, customerName, lat, lng) {
        document.getElementById('map-container').style.display = 'block';
        document.getElementById('map-title').innerText = `Tracking Order #${orderId} for ${customerName}`;
        document.getElementById('update-order-id').value = orderId;
        
        // Initialize map if not already done
        if (!map) {
            initMap();
        }
        
        // If we have location data for this order
        if (lat && lng) {
            map.setView([lat, lng], 15);
            
            // Update or create marker
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`Order #${orderId} - ${customerName}<br>Last updated: ${new Date().toLocaleString()}`).openPopup();
            }
            
            document.getElementById('update-lat').value = lat;
            document.getElementById('update-lng').value = lng;
        } else {
            // No location yet - use a default or random location
            const defaultLocation = <?php echo json_encode(getRandomLocation()); ?>;
            map.setView([defaultLocation.latitude, defaultLocation.longitude], 12);
            
            if (marker) {
                map.removeLayer(marker);
                marker = null;
            }
            
            document.getElementById('update-lat').value = defaultLocation.latitude;
            document.getElementById('update-lng').value = defaultLocation.longitude;
            
            alert('No real-time location available for this order yet. Using default location.');
        }
        
        // Force map to recalculate size
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }

    function useCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('update-lat').value = lat;
                    document.getElementById('update-lng').value = lng;
                    
                    if (map && marker) {
                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);
                    } else if (map) {
                        map.setView([lat, lng], 15);
                        marker = L.marker([lat, lng]).addTo(map);
                    }
                },
                () => {
                    alert('Unable to retrieve your location. Please enter manually.');
                }
            );
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    }

    // Contact Modal
    function openContactModal(orderId, name, phone) {
        document.getElementById('contactModal').style.display = 'block';
        document.getElementById('customer-name').innerText = 'Name: ' + name;
        
        // Update contact links
        document.getElementById('call-customer').href = 'tel:' + phone;
        document.getElementById('whatsapp-customer').href = 'https://wa.me/' + phone.replace(/[^0-9]/g, '');
        document.getElementById('sms-customer').href = 'sms:' + phone;
    }

    function closeContactModal() {
        document.getElementById('contactModal').style.display = 'none';
    }

    // When user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        const modal = document.getElementById('contactModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Open "Total" by default when page loads
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelector('.tablinks').click();
    });
</script>
</body>
</html>