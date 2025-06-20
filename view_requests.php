<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'lawyer') {
    header("Location: ../auth/login.php");
    exit;
}

$lawyer_id = $_SESSION['user_id'];

// Handle Accept/Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'])) {
    $consultation_id = intval($_POST['id']);
    $action = $_POST['action'];

    if (in_array($action, ['accept', 'reject'])) {
        $stmt = $pdo->prepare("SELECT id FROM consultations WHERE id = ? AND lawyer_id = ? AND status = 'pending'");
        $stmt->execute([$consultation_id, $lawyer_id]);

        if ($stmt->rowCount() > 0) {
            if ($action === 'accept') {
                $update = $pdo->prepare("UPDATE consultations SET status = 'accepted' WHERE id = ?");
                $update->execute([$consultation_id]);
            } else {
                $delete = $pdo->prepare("DELETE FROM consultations WHERE id = ?");
                $delete->execute([$consultation_id]);
            }
            $_SESSION['flash'] = ($action === 'accept') ? "Consultation accepted." : "Consultation rejected and removed.";
        } else {
            $_SESSION['flash'] = "Invalid consultation or already handled.";
        }

        header("Location: view_requests.php");
        exit;
    }
}

// Fetch requests
$stmt = $pdo->prepare("
    SELECT c.id, u.full_name AS client_name, c.message, c.status, c.created_at
    FROM consultations c
    JOIN users u ON c.client_id = u.id
    WHERE c.lawyer_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$lawyer_id]);
$requests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consultation Requests – JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1b1f3b, #2c2e4d);
            font-family: 'Urbanist', sans-serif;
            color: #fff;
            padding: 40px 20px;
        }

        h2 {
            text-align: center;
            color: #f5c518;
            margin-bottom: 30px;
        }

        .flash {
            text-align: center;
            color: #f5c518;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        th {
            background-color: #26324d;
            color: #f5c518;
            font-weight: 600;
        }

        td {
            color: #eee;
        }

        .status {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.9em;
            text-transform: capitalize;
        }

        .status.pending { background: #ffa50033; color: #ffa500; }
        .status.accepted { background: #28a74533; color: #28a745; }
        .status.rejected { background: #dc354533; color: #dc3545; }
        .status.completed { background: #007bff33; color: #007bff; }

        form, .msg-button {
            display: inline;
        }

        button, .msg-button a {
            background: #f5c518;
            color: #1b1f3b;
            border: none;
            padding: 8px 16px;
            margin: 0 5px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        button:hover, .msg-button a:hover {
            background: #e0b713;
        }

        p {
            text-align: center;
            font-size: 1.1em;
            color: #bbb;
        }

        @media (max-width: 700px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            tr {
                margin-bottom: 20px;
                background: rgba(255,255,255,0.05);
                padding: 15px;
                border-radius: 10px;
            }

            th {
                display: none;
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 10px 5px;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #f5c518;
            }
        }
    </style>
</head>
<body>

    <h2>📥 Consultation Requests</h2>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash"><?= htmlspecialchars($_SESSION['flash']) ?></div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if (!$requests): ?>
        <p>No consultation requests yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $r): ?>
                    <tr>
                        <td data-label="Client"><?= htmlspecialchars($r['client_name']) ?></td>
                        <td data-label="Message"><?= htmlspecialchars($r['message']) ?></td>
                        <td data-label="Status">
                            <span class="status <?= $r['status'] ?>">
                                <?= htmlspecialchars($r['status']) ?>
                            </span>
                        </td>
                        <td data-label="Action">
                            <?php if ($r['status'] === 'pending'): ?>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <button name="action" value="accept">Accept</button>
                                    <button name="action" value="reject">Reject</button>
                                </form>
                            <?php elseif ($r['status'] === 'accepted'): ?>
                                <div class="msg-button">
                                    <a href="../messages/chat.php?consultation_id=<?= $r['id'] ?>">💬 Message</a>
                                </div>
                            <?php else: ?>
                                <?= ucfirst($r['status']) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
