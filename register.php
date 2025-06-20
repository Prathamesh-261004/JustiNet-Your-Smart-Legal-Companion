<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role']; // 'client' or 'lawyer'

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: register.php");
        exit;
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: register.php");
        exit;
    }

    // Hash password and insert
    $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $hashed_pass, $role]);

    $_SESSION['success'] = "Registration successful. Please login.";
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: linear-gradient(135deg, #2c2e4d, #1b1f3b);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .register-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            color: #fff;
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        .register-box h2 {
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        input, select {
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

        .success {
            color: #00e676;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Create Your Account</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p class="error">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p class="success">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);
    }
    ?>

    <form method="POST" action="">
        <input type="text" name="full_name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="client">Client</option>
            <option value="lawyer">Lawyer</option>
        </select><br>
        <button type="submit">Register</button>
    </form>

    <a href="login.php">Already have an account? Login</a>
</div>

</body>
</html>
