<?php
require_once '../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #001f3f;
            color: #ffeb3b;
            margin: 0;
            padding: 40px;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            font-size: 28px;
            text-align: center;
            color: #ffeb3b;
            margin-bottom: 20px;
            text-shadow: 1px 1px 3px #000;
            animation: slideDown 0.8s ease-out;
        }

        p {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
            color: #ffe066;
        }

        ul {
            max-width: 400px;
            margin: 30px auto;
            padding: 0;
            list-style: none;
            animation: fadeInUp 1s ease;
        }

        ul li {
            background: #0d1b2a;
            padding: 15px 20px;
            margin: 12px 0;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4);
            transition: background 0.3s ease;
        }

        ul li a {
            color: #ffeb3b;
            text-decoration: none;
            font-weight: bold;
            display: block;
        }

        ul li:hover {
            background: #14334f;
        }

        a {
            color: #ffe066;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?> (Admin)</h2>
    <p>📌 Admin Tools:</p>

    <ul>
        <li><a href="../admin/users.php">👤 Manage Users</a></li>
        <li><a href="../admin/consultations.php">🗂 Consultation Logs</a></li>
        <li><a href="../admin/reports.php">📈 Reports & Analytics</a></li>
    </ul>

    <a href="../auth/logout.php">🚪 Logout</a>

</body>
</html>
