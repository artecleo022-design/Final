<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name('student_portal');
    session_start();
}
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_org_id'])) {
    $org_id = $_POST['apply_org_id'];
    
    // Check if already has PENDING or APPROVED application for this specific org
    $stmtApp = $pdo->prepare("SELECT id FROM applications WHERE student_id = ? AND org_id = ? AND status IN ('PENDING', 'APPROVED')");
    $stmtApp->execute([$user_id, $org_id]);
    
    if (!$stmtApp->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO applications (student_id, org_id, status) VALUES (?, ?, 'PENDING')");
        if ($stmt->execute([$user_id, $org_id])) {
            header("Location: dashboard.php?msg=" . urlencode("Successfully applied to the organization!"));
            exit;
        }
    } else {
        header("Location: dashboard.php?msg=" . urlencode("You already have a pending or approved application for this organization.") . "&error=1");
        exit;
    }
}

header("Location: organizations.php");
exit;
