<?php
session_start();

// Show welcome popup only once after login
$showWelcomePopup = false;
if (isset($_SESSION['loggedin'], $_SESSION['show_welcome']) && $_SESSION['loggedin'] === true && $_SESSION['show_welcome'] === true) {
    $showWelcomePopup = true;
    $_SESSION['show_welcome'] = false; // Reset to not show again
}
?>

<?php if ($showWelcomePopup): ?>
    <div class="welcome-popup">
        👋 Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!
    </div>
<?php endif; ?>

<style>
/* Welcome popup styling */
.welcome-popup {
    background-color:rgb(194, 207, 209);
    color: #00796b;
    padding: 12px 20px;
    text-align: center;
    font-weight: bold;
    font-size: 16px;
}

/* Profile dropdown styling */
.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    z-index: 1;
    border-radius: 6px;
    overflow: hidden;
}

.profile-dropdown-content a {
    color: black;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.profile-dropdown-content a:hover {
    background-color: #f5f5f5;
}

.profile-dropdown:hover .profile-dropdown-content {
    display: block;
}
/* Default styles for all devices */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Styles for mobile devices (screen width up to 768px) */
@media (max-width: 768px) {
  .container {
    padding: 10px;
  }
}

/* Styles for desktop devices (screen width 769px and above) */
@media (min-width: 769px) {
  .container {
    padding: 20px;
  }
}
</style>

<header>
    <div class="logo_container">
        <a href="indexs.php"><img class="myntra_home" src="th.jpeg" alt="Hexashop Home"></a>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" /> 
    </div>

   

    <div class="search_bar">
        <span class="material-symbols-outlined search_icon">search</span>
        <input class="search_input" placeholder="Search for products, brands and more">
    </div>

    <div class="action_bar">
        <div class="action_container profile-dropdown">
            <span class="material-symbols-outlined action_icon">account_circle</span>
            <span>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    Hello, <?= htmlspecialchars($_SESSION['name']) ?>
                <?php else: ?>
                    <a href="login.php" style="text-decoration:none; color:black;">Login</a>
                <?php endif; ?>
            </span>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="profile-dropdown-content">
                    <a href="profile.php">👤 View Profile</a>
                    <a href="track_order.php">📦 Your Orders</a>
                    <a href="logout.php">🚪 Logout</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="action_container">
            <a href="cart.php" style="text-decoration: none; color: inherit;">
                <span class="material-symbols-outlined action_icon">shopping_bag</span>
                <span class="action_name">Bag</span>
            </a>
        </div>
    </div>
</header>
