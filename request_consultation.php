<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}

$client_id = $_SESSION['user_id'];
$lawyer_id = $_GET['lawyer_id'] ?? null;

$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lawyer_id = intval($_POST['lawyer_id']);
    $message = trim($_POST['message']);

    if ($message && $lawyer_id) {
        $stmt = $pdo->prepare("INSERT INTO consultations (client_id, lawyer_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$client_id, $lawyer_id, $message]);

        $success = "✅ Consultation requested successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Consultation – JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #1b1f3b, #2c2e4d);
            color: #fff;
            font-family: 'Urbanist', sans-serif;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: #f5c518;
            margin-bottom: 20px;
        }

        form {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 16px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(12px);
        }

        textarea {
            width: 100%;
            height: 120px;
            padding: 12px;
            font-size: 1em;
            border-radius: 8px;
            border: none;
            resize: none;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            background: #f5c518;
            color: #1b1f3b;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #e0b713;
        }

        .success {
            background: #2e8b57;
            color: #fff;
            padding: 12px;
            margin-top: 20px;
            border-radius: 8px;
            max-width: 500px;
            text-align: center;
        }

        @media (max-width: 600px) {
            form {
                padding: 20px;
            }

            h2 {
                font-size: 1.5em;
            }

            button {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>

    <h2>📝 Request a Consultation</h2>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="lawyer_id" value="<?= htmlspecialchars($lawyer_id) ?>">
            <textarea name="message" placeholder="Briefly explain your legal issue..." required></textarea>
            <button type="submit">Submit Request</button>
        </form>
    <?php endif; ?>
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
