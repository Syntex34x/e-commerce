<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$name = $_POST['name'];
$newPassword = $_POST['new_password'] ?? '';
$profilePicPath = '';

// Load users from JSON file
$userFile = 'users.json';
$users = file_exists($userFile) ? json_decode(file_get_contents($userFile), true) : [];

// Update the user's profile information
foreach ($users as &$user) {
    if ($user['username'] === $username) {
        $user['name'] = $name;
        if (!empty($newPassword)) {
            $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Upload new profile picture if selected
        if (!empty($_FILES['profile_pic']['name'])) {
            $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
            $target = 'uploads/' . uniqid('avatar_') . '.' . $ext;
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target);
            $user['profile_pic'] = $target;
            $_SESSION['profile_pic'] = $target;
        }

        $_SESSION['name'] = $user['name'];
        break;
    }
}

// Save the updated users back to the JSON file
file_put_contents($userFile, json_encode($users, JSON_PRETTY_PRINT));

header('Location: profile.php');
exit;
?>
