<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}

$query = $_GET['q'] ?? '';
$stmt = $pdo->prepare("
    SELECT u.id, u.full_name, lp.specialization, lp.city, lp.consultation_fee
    FROM users u
    JOIN lawyer_profiles lp ON u.id = lp.user_id
    WHERE u.role = 'lawyer' AND (lp.city LIKE ? OR lp.specialization LIKE ?)
");
$stmt->execute(["%$query%", "%$query%"]);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Lawyers – JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #1b1f3b, #2c2e4d);
            color: #fff;
            font-family: 'Urbanist', sans-serif;
            padding: 30px;
        }

        h2 {
            color: #f5c518;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        input[name="q"] {
            padding: 10px 15px;
            width: 60%;
            max-width: 400px;
            border-radius: 8px 0 0 8px;
            border: none;
            outline: none;
            font-size: 1em;
        }

        button {
            padding: 10px 18px;
            border: none;
            border-radius: 0 8px 8px 0;
            background: #f5c518;
            color: #1b1f3b;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #e0b713;
        }

        .lawyer-card {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            transition: transform 0.2s ease;
        }

        .lawyer-card:hover {
            transform: translateY(-3px);
        }

        .lawyer-card strong {
            font-size: 1.3em;
            color: #f5c518;
        }

        .lawyer-card .meta {
            margin: 5px 0;
            color: #ccc;
        }

        .lawyer-card .fee {
            margin-top: 8px;
            font-weight: bold;
            color: #85e085;
        }

        .lawyer-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 18px;
            background: #f5c518;
            color: #1b1f3b;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .lawyer-card a:hover {
            background: #e0b713;
        }

        @media (max-width: 600px) {
            input[name="q"] {
                width: 100%;
                border-radius: 8px;
                margin-bottom: 10px;
            }

            form {
                flex-direction: column;
                align-items: center;
            }

            button {
                width: 100%;
                border-radius: 8px;
            }
        }
    </style>
</head>
<body>

    <h2>🔍 Find a Lawyer</h2>

    <form method="GET">
        <input name="q" placeholder="Search by city or specialization" value="<?= htmlspecialchars($query) ?>">
        <button type="submit">Search</button>
    </form>

    <?php if ($results): ?>
        <?php foreach ($results as $lawyer): ?>
            <div class="lawyer-card">
                <strong><?= htmlspecialchars($lawyer['full_name']) ?></strong>
                <div class="meta"><?= htmlspecialchars($lawyer['specialization']) ?> — <?= htmlspecialchars($lawyer['city']) ?></div>
                <div class="fee">💰 Fee: ₹<?= number_format($lawyer['consultation_fee'], 2) ?></div>
                <a href="request_consultation.php?lawyer_id=<?= $lawyer['id'] ?>">Request Consultation</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; color: #bbb;">No lawyers found for "<strong><?= htmlspecialchars($query) ?></strong>".</p>
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
