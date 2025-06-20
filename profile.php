<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'lawyer') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";

// On form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $specialization = trim($_POST['specialization']);
    $experience = intval($_POST['experience']);
    $bio = trim($_POST['bio']);
    $contact = trim($_POST['contact']);
    $city = trim($_POST['city']);
    $fee = floatval($_POST['fee']);

    // Upsert profile
    $stmt = $pdo->prepare("REPLACE INTO lawyer_profiles (user_id, specialization, experience_years, bio, contact_number, city, consultation_fee) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $specialization, $experience, $bio, $contact, $city, $fee]);

    $success = "✅ Profile updated successfully.";
}

// Load profile if exists
$stmt = $pdo->prepare("SELECT * FROM lawyer_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Lawyer Profile – JustiNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1b1f3b, #2c2e4d);
            font-family: 'Urbanist', sans-serif;
            color: #fff;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-box {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
            padding: 35px;
            border-radius: 14px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }

        .profile-box h2 {
            font-size: 1.8em;
            color: #f5c518;
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-box input,
        .profile-box textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .profile-box textarea {
            resize: vertical;
            min-height: 100px;
        }

        .profile-box button {
            background: #f5c518;
            color: #1b1f3b;
            border: none;
            padding: 14px 20px;
            font-weight: bold;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.3s ease;
        }

        .profile-box button:hover {
            background: #e0b713;
        }

        .success-msg {
            background: #28a745;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 600px) {
            .profile-box {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>

<div class="profile-box">
    <h2>👤 Lawyer Profile</h2>

    <?php if (!empty($success)): ?>
        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input name="specialization" placeholder="Specialization" value="<?= htmlspecialchars($profile['specialization'] ?? '') ?>" required>
        <input name="experience" type="number" placeholder="Years of Experience" value="<?= htmlspecialchars($profile['experience_years'] ?? '') ?>" required>
        <input name="contact" placeholder="Contact Number" value="<?= htmlspecialchars($profile['contact_number'] ?? '') ?>" required>
        <input name="city" placeholder="City" value="<?= htmlspecialchars($profile['city'] ?? '') ?>" required>
        <input name="fee" type="number" step="0.01" placeholder="Consultation Fee (₹)" value="<?= htmlspecialchars($profile['consultation_fee'] ?? '') ?>" required>
        <textarea name="bio" placeholder="Short Bio"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
        <button type="submit">💾 Save Profile</button>
    </form>
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
