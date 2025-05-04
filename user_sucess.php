<?php
session_start();
if (!isset($_SESSION['new_user'])) {
    header('Location: admin_home.php');
    exit;
}

$newUser = $_SESSION['new_user'];
unset($_SESSION['new_user']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Created Successfully</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --text-color: #333;
            --light-color: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: var(--light-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            background: white;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s ease-in;
        }

        .success-icon {
            font-size: 4rem;
            color: var(--success-color);
            margin-bottom: 1.5rem;
        }

        h1 {
            color: var(--success-color);
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        .user-details {
            text-align: left;
            background-color: var(--light-color);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .user-details p {
            margin: 0.8rem 0;
            font-size: 1.1rem;
        }

        .back-link {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 1.5rem;
        }

        .back-link:hover {
            background-color: var(--secondary-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .success-container {
                padding: 1.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .user-details p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h1>User Created Successfully!</h1>
        
        <div class="user-details">
            <p><strong>Username:</strong> <?= htmlspecialchars($newUser['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($newUser['role']) ?></p>
            <p><strong>Created At:</strong> <?= date('M j, Y H:i', strtotime($newUser['created_at'])) ?></p>
        </div>

        <a href="admin_home.php" class="back-link">Back to User Management</a>
    </div>
</body>
</html>