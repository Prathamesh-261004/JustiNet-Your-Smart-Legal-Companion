<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consultation_id = intval($_POST['consultation_id']);
    $message = trim($_POST['message']);

    // Verify consultation access
    $stmt = $pdo->prepare("SELECT * FROM consultations WHERE id = ? AND status = 'accept' AND (client_id = ? OR lawyer_id = ?)");
    $stmt->execute([$consultation_id, $user_id, $user_id]);
    if (!$stmt->fetch()) {
        die("Unauthorized.");
    }

    // Insert message
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, consultation_id, message) VALUES (?, ?, ?, ?)");

    // Determine receiver
    $receiver_id_stmt = $pdo->prepare("SELECT client_id, lawyer_id FROM consultations WHERE id = ?");
    $receiver_id_stmt->execute([$consultation_id]);
    $cons = $receiver_id_stmt->fetch();
    $receiver_id = ($cons['client_id'] == $user_id) ? $cons['lawyer_id'] : $cons['client_id'];

    $stmt->execute([$user_id, $receiver_id, $consultation_id, $message]);

    // Redirect back to chat
    header("Location: chat.php?id=$consultation_id");
    exit;
}
