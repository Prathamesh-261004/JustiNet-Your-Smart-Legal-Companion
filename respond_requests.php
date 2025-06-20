<?php
session_start();
require_once '../includes/auth.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'lawyer') {
    header("Location: ../auth/login.php");
    exit;
}

$lawyer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'])) {
    $consultation_id = intval($_POST['id']);
    $action = $_POST['action'];

    if (in_array($action, ['accept', 'reject'])) {
        // Verify consultation belongs to lawyer and is pending
        $stmt = $pdo->prepare("SELECT id FROM consultations WHERE id = ? AND lawyer_id = ? AND status = 'pending'");
        $stmt->execute([$consultation_id, $lawyer_id]);

        if ($stmt->rowCount() > 0) {
            $update = $pdo->prepare("UPDATE consultations SET status = ? WHERE id = ?");
            $update->execute([$action, $consultation_id]);

            if ($update->rowCount() > 0) {
                $_SESSION['flash'] = "Consultation has been " . $action . "ed successfully.";
            } else {
                $_SESSION['flash'] = "No changes made. Try again.";
            }
        } else {
            $_SESSION['flash'] = "Invalid consultation request.";
        }
    } else {
        $_SESSION['flash'] = "Invalid action.";
    }
} else {
    $_SESSION['flash'] = "Invalid request.";
}

header("Location: view_requests.php");
exit;
