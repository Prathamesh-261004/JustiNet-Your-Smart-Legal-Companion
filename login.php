<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Hardcoded admin credentials
    $admin_email = 'admin@portal.com';
    $admin_password = 'admin123'; // Ideally hash this and compare

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['user_id'] = 0;
        $_SESSION['role'] = 'admin';
        $_SESSION['name'] = 'Admin';

        header("Location: ../dashboard/admin_dashboard.php");
        exit;
    }

    // Check other users
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['full_name'];

        switch ($user['role']) {
            case 'lawyer':
                header("Location: ../dashboard/lawyer_dashboard.php");
                break;
            case 'client':
                header("Location: ../dashboard/client_dashboard.php");
                break;
        }
        exit;
    } else {
        $_SESSION['error'] = "Invalid credentials.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: linear-gradient(135deg, #1b1f3b, #2e2f52);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            color: #fff;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px 15px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
            font-size: 1em;
        }

        button {
            background: #f5c518;
            color: #1b1f3b;
            border: none;
            padding: 12px 30px;
            margin-top: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
        }

        button:hover {
            background: #e4b213;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #f5c518;
            text-decoration: none;
            font-weight: bold;
        }

        .error {
            color: #ff6666;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login to JustiNet</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p class="error">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <a href="register.php">Don't have an account? Register</a>
</div>

</body>
</html>
