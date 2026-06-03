<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    // Update login_logs
    try {
        $stmt = $pdo->prepare("UPDATE login_logs SET logout_time = NOW(), status = 'OFFLINE' WHERE user_id = ? AND status = 'ONLINE'");
        $stmt->execute([$userId]);
    } catch (PDOException $e) {
        // error handling silently on logout
    }
}

// Destroy session
session_unset();
session_destroy();

header("Location: index.php");
exit;
?>
