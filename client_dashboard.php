<?php
require_once '../includes/auth.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard – JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1b1f3b, #2c2e4d);
            color: #fff;
            font-family: 'Urbanist', sans-serif;
            padding: 40px 20px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h2 {
            color: #f5c518;
            margin-bottom: 10px;
            font-size: 2em;
        }

        .actions {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .actions p {
            font-size: 1.1em;
            color: #ccc;
            margin-bottom: 20px;
        }

        .actions a {
            display: block;
            background: #f5c518;
            color: #1b1f3b;
            padding: 12px 20px;
            margin: 10px 0;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .actions a:hover {
            background: #e0b713;
        }

        .logout-link {
            margin-top: 30px;
            font-size: 0.95em;
            color: #bbb;
        }

        .logout-link a {
            color: #f5c518;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .actions {
                padding: 20px;
            }

            h2 {
                font-size: 1.5em;
            }

            .actions a {
                font-size: 1em;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <h2>👋 Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h2>

    <div class="actions">
        <p>📌 Choose an action:</p>
        <a href="../client/search_lawyers.php">🔍 Search Lawyers</a>
        <a href="../client/request_consultation.php">📨 Request Consultation</a>
        <a href="../client/view_status.php">📊 View Request Status</a>
    </div>

    <div class="logout-link">
        <p><a href="../auth/logout.php">Logout</a></p>
    </div>
<a href="../index.php" class="float-btn" title="Back to Home">
    <span>⟵</span>
</a>
<style>
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
</style>

</body>
</html>
