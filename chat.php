<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$consultation_id = $_GET['consultation_id'] ?? $_POST['consultation_id'] ?? null;
if (!$consultation_id || !is_numeric($consultation_id)) {
    die("Consultation not specified.");
}

$stmt = $pdo->prepare("SELECT * FROM consultations WHERE id = ? AND status = 'accepted' AND (client_id = ? OR lawyer_id = ?)");
$stmt->execute([$consultation_id, $user_id, $user_id]);
$consultation = $stmt->fetch();
if (!$consultation) {
    die("Unauthorized or invalid consultation.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $receiver_id = ($consultation['client_id'] == $user_id) ? $consultation['lawyer_id'] : $consultation['client_id'];
        $insert = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, consultation_id, message, sent_at) VALUES (?, ?, ?, ?, NOW())");
        $insert->execute([$user_id, $receiver_id, $consultation_id, $message]);
    }
    header("Location: chat.php?consultation_id=$consultation_id");
    exit;
}

$partner_id = ($consultation['client_id'] == $user_id) ? $consultation['lawyer_id'] : $consultation['client_id'];
$stmt = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->execute([$partner_id]);
$partner_name = $stmt->fetchColumn();

$msgs = $pdo->prepare("SELECT * FROM messages WHERE consultation_id = ? ORDER BY sent_at ASC");
$msgs->execute([$consultation_id]);
$chat = $msgs->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chat – JustiNet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #001f3f;
            padding: 40px;
            color: #ffeb3b;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: #ffeb3b;
            letter-spacing: 1px;
            text-shadow: 1px 1px 4px #000;
            animation: slideDown 0.8s ease-out;
        }

        .chat-box {
            border: 1px solid #444;
            padding: 15px;
            background: #0a0a23;
            max-width: 800px;
            margin: 0 auto 20px;
            border-radius: 10px;
            max-height: 450px;
            overflow-y: auto;
            box-shadow: 0 0 15px #111;
            animation: fadeIn 1s ease;
        }

        .chat-box p {
            margin: 12px 0;
            padding: 12px 15px;
            border-radius: 8px;
            max-width: 70%;
            font-size: 16px;
            background: #222;
            color: #ffeb3b;
            box-shadow: 1px 1px 6px #00000060;
            animation: messagePop 0.4s ease;
        }

        .you {
            background: #004d99;
            align-self: flex-end;
            margin-left: auto;
        }

        .them {
            background: #660000;
            align-self: flex-start;
            margin-right: auto;
        }

        .chat-form {
            text-align: center;
            max-width: 800px;
            margin: auto;
            animation: slideUp 1s ease;
        }

        textarea {
            width: 80%;
            padding: 12px;
            font-size: 1em;
            border: 1px solid #888;
            border-radius: 6px;
            resize: vertical;
            font-family: 'Poppins', sans-serif;
            background: #111;
            color: #ffeb3b;
            box-shadow: 1px 1px 4px #000;
        }

        button {
            padding: 12px 20px;
            margin-left: 10px;
            background: #ffd600;
            color: #000;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background 0.3s ease;
            box-shadow: 1px 1px 6px #222;
        }

        button:hover {
            background: #fff176;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #ffeb3b;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes messagePop {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        small {
            display: block;
            font-size: 0.8em;
            color: #cccccc;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<h2>Chat with <?= htmlspecialchars($partner_name) ?></h2>

<div class="chat-box">
    <?php foreach ($chat as $msg): ?>
        <p class="<?= $msg['sender_id'] == $user_id ? 'you' : 'them' ?>">
            <strong><?= $msg['sender_id'] == $user_id ? 'You' : htmlspecialchars($partner_name) ?>:</strong><br>
            <?= nl2br(htmlspecialchars($msg['message'])) ?>
            <small><em><?= date('M d, H:i', strtotime($msg['sent_at'])) ?></em></small>
        </p>
    <?php endforeach; ?>
</div>

<div class="chat-form">
    <form method="POST">
        <input type="hidden" name="consultation_id" value="<?= htmlspecialchars($consultation_id) ?>">
        <textarea name="message" placeholder="Type your message..." required rows="3"></textarea>
        <button type="submit">Send</button>
    </form>
</div>

<div class="back-link">
    <p><a href="../dashboard/<?= $role ?>_dashboard.php">← Back to Dashboard</a></p>
</div>

</body>
</html>
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

