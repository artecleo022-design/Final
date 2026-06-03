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

$current_page = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal | ADSSU</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0F5132;
            --secondary: #FFC107;
            --bg-color: #F8F9FA;
            --text-main: #212529;
            --card-bg: #FFFFFF;
        }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: var(--bg-color); color: var(--text-main); display: flex; min-height: 100vh; flex-direction: column; }
        
        .layout-wrapper { display: flex; flex: 1; flex-direction: column; }
        @media (min-width: 768px) {
            .layout-wrapper { flex-direction: row; }
        }

        .sidebar { width: 100%; background: #1e3a2f; color: white; padding: 20px 0; display: flex; flex-direction: column; }
        @media (min-width: 768px) {
            .sidebar { width: 250px; min-height: 100vh; position: sticky; top: 0; }
        }

        .sidebar-logo { text-align: center; margin-bottom: 20px; }
        .sidebar-logo img { width: 80px; }
        .sidebar-links { display: flex; flex-direction: column; }
        @media (max-width: 767px) {
            .sidebar-links { flex-direction: row; flex-wrap: wrap; justify-content: center; }
            .sidebar-links a { flex: 1 1 auto; text-align: center; padding: 10px; font-size: 14px;}
        }

        .sidebar a { color: #adb5bd; text-decoration: none; padding: 15px 20px; display: block; font-weight: 600; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--secondary); color: #1e3a2f; border-left: 4px solid white; }

        .main-content { flex: 1; padding: 30px; }
        .header { margin-bottom: 30px; }
        .header h1 { margin: 0; color: var(--primary); font-weight: 800; }

        .card { background: var(--card-bg); border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card-title { font-size: 20px; font-weight: 800; color: var(--primary); margin-bottom: 15px; }
        
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; font-family: inherit; transition: 0.3s; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #157347; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #bb2d3b; }
        .btn-disabled { background: #e2e8f0; color: #a0aec0; cursor: not-allowed; }
        
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 12px; font-weight: 800; font-size: 12px; }
        .status-PENDING { background: #ffeeba; color: #856404; }
        .status-APPROVED { background: #d4edda; color: #155724; }
        .status-REJECTED { background: #f8d7da; color: #721c24; }
        
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ced4da; border-radius: 5px; box-sizing: border-box; font-family: inherit;}
        .msg { padding: 15px; border-radius: 5px; background: #d1e7dd; color: #0f5132; margin-bottom: 20px; font-weight: 600; }
        .msg-error { background: #f8d7da; color: #842029; }

        label { display: block; font-weight: 600; margin-bottom: 5px; color: #555; font-size: 14px; }
    </style>
</head>
<body>
<div class="layout-wrapper">
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="../adssu.png" alt="Logo">
            <h3 style="margin:10px 0; font-size:16px;">Student Portal</h3>
        </div>
        <div class="sidebar-links">
            <a href="dashboard.php" class="<?= $current_page=='dashboard.php'?'active':'' ?>">Dashboard</a>
            <a href="organizations.php" class="<?= $current_page=='organizations.php'?'active':'' ?>">Organizations</a>
            <a href="announcements.php" class="<?= $current_page=='announcements.php'?'active':'' ?>">Announcements</a>
            <a href="profile.php" class="<?= $current_page=='profile.php'?'active':'' ?>">Edit Profile</a>
            <a href="logout.php" style="margin-top:auto; color:#ffc107;">Logout</a>
        </div>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['fullname']) ?>!</h1>
        </div>
