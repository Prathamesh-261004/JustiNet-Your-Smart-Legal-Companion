<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $pdo->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Users – Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #001f3f;
            color: #ffeb3b;
            padding: 40px;
            animation: fadeIn 0.7s ease-in-out;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #ffeb3b;
            text-shadow: 1px 1px 3px #000;
        }

        table {
            width: 100%;
            max-width: 1000px;
            margin: auto;
            border-collapse: collapse;
            background: #0d1b2a;
            color: #ffe066;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            animation: slideUp 0.8s ease;
        }

        table th, table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #1a2e40;
        }

        table th {
            background: #14334f;
            color: #ffeb3b;
            font-size: 16px;
        }

        table tr:hover {
            background-color: #1c3c59;
        }

        .float-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ffd600, #fff176);
            color: #000;
            border-radius: 50%;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .float-btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
            background: linear-gradient(135deg, #fff176, #ffee58);
        }

        .float-btn span {
            font-weight: bold;
            text-shadow: 1px 1px 2px #00000050;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

<h2>All Registered Users</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Registered At</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['full_name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= ucfirst($user['role']) ?></td>
            <td><?= date('M d, Y H:i', strtotime($user['created_at'])) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="../index.php" class="float-btn" title="Back to Home">
    <span>⟵</span>
</a>

</body>
</html>
