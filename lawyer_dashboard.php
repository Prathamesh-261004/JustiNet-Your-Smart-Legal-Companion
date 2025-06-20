<?php
require_once '../includes/auth.php';

if ($_SESSION['role'] !== 'lawyer') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lawyer Dashboard – JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Urbanist', sans-serif;
            background: linear-gradient(135deg, #151d2f, #1e2640);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px 20px;
            min-height: 100vh;
        }

        h2 {
            font-size: 2.5em;
            font-weight: 700;
            margin-bottom: 20px;
            color: #f5c518;
        }

        .dashboard-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }

        .dashboard-box p {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #ddd;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .actions a {
            display: block;
            background: #f5c518;
            color: #1b1f3b;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .actions a:hover {
            background: #e0b713;
            transform: translateY(-2px);
        }

        .logout {
            margin-top: 30px;
            color: #aaa;
            font-size: 0.95em;
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .dashboard-box {
                padding: 25px 20px;
            }

            h2 {
                font-size: 2em;
            }

            .actions a {
                font-size: 1em;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <h2>👨‍⚖️ Hello, <?= htmlspecialchars($_SESSION['name']) ?></h2>

    <div class="dashboard-box">
        <p>Welcome to your Lawyer Dashboard. Choose an action:</p>
        <div class="actions">
            <a href="../lawyer/profile.php">📝 Edit Your Profile</a>
            <a href="../lawyer/view_requests.php">📥 View Consultation Requests</a>
           <!-- <a href="../messages/chat.php">💬 Open Client Chat</a>-->
        </div>

        <a class="logout" href="../auth/logout.php">🚪 Logout</a>
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
