<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}

$client_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT c.id, u.full_name AS lawyer_name, c.message, c.status, c.created_at
    FROM consultations c
    JOIN users u ON c.lawyer_id = u.id
    WHERE c.client_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$client_id]);
$consults = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Consultations – JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #1b1f3b, #2c2e4d);
            color: #fff;
            font-family: 'Urbanist', sans-serif;
            padding: 40px 20px;
        }

        h2 {
            color: #f5c518;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            max-width: 900px;
            margin: auto;
            border-collapse: collapse;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        th, td {
            padding: 15px 18px;
            text-align: left;
            color: #eee;
        }

        th {
            background: rgba(245, 197, 24, 0.1);
            color: #f5c518;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        tr:nth-child(even) {
            background: rgba(255,255,255,0.03);
        }

        .status {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }

        .pending {
            background: #ff9800;
            color: #000;
        }

        .accepted {
            background: #4caf50;
            color: #fff;
        }

        .rejected {
            background: #f44336;
            color: #fff;
        }

        .completed {
            background: #2196f3;
            color: #fff;
        }

        .message-button {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background: #f5c518;
            color: #000;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
        }

        .message-button:hover {
            background: #e0b713;
        }

        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            th {
                text-align: left;
                background: none;
                padding: 10px 5px;
                font-size: 1em;
            }

            td {
                padding: 10px 5px;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }

            tr {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<h2>📋 Your Consultation Requests</h2>

<?php if (empty($consults)): ?>
    <p style="text-align: center; color: #ccc;">No consultation requests found.</p>
<?php else: ?>
<table>
    <tr>
        <th>Lawyer</th>
        <th>Message</th>
        <th>Status</th>
        <th>Date</th>
    </tr>
    <?php foreach ($consults as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['lawyer_name']) ?></td>
        <td><?= htmlspecialchars($row['message']) ?></td>
        <td>
            <span class="status <?= strtolower($row['status']) ?>">
                <?= ucfirst($row['status']) ?>
            </span>
            <?php if (strtolower($row['status']) === 'accepted'): ?>
                <br>
                <a class="message-button" href="../messages/chat.php?consultation_id=<?= $row['id'] ?>">💬 Message</a>
            <?php endif; ?>
        </td>
        <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
    </tr>
    <?php endforeach; ?>
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
