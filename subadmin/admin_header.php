<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name('subadmin_portal');
    session_start();
}
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sub_admin') {
    header("Location: login.php");
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sub Admin Portal | ADSSU</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0F5132;
            --secondary: #FFC107;
            --bg-color: #F8F9FA;
            --text-main: #212529;
            --card-bg: #FFFFFF;
        }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: var(--bg-color); color: var(--text-main); display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #1e3a2f; color: white; padding: 20px 0; display: flex; flex-direction: column; }
        .sidebar-logo { text-align: center; margin-bottom: 20px; }
        .sidebar-logo img { width: 80px; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 15px 20px; display: block; font-weight: 600; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--secondary); color: #1e3a2f; border-left: 4px solid white; }
        
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        .header { margin-bottom: 30px; }
        .header h1 { margin: 0; color: var(--primary); font-weight: 800; }

        .card { background: var(--card-bg); border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card-title { font-size: 20px; font-weight: 800; color: var(--primary); margin-bottom: 15px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; }
        th { background: #f8f9fa; color: #495057; font-weight: 800; text-transform: uppercase; font-size: 12px; }

        .btn { padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; font-family: inherit; transition: 0.3s; }
        .btn-success { background: var(--primary); color: white; }
        .btn-success:hover { background: #157347; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #bb2d3b; }
        .btn-primary { background: var(--primary); color: white; }
        
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 12px; font-weight: 800; font-size: 12px; }
        .status-PENDING { background: #ffeeba; color: #856404; }
        .status-APPROVED { background: #d4edda; color: #155724; }
        .status-REJECTED { background: #f8d7da; color: #721c24; }
        
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ced4da; border-radius: 5px; box-sizing: border-box; font-family: inherit;}
        .msg { padding: 15px; border-radius: 5px; background: #d1e7dd; color: #0f5132; margin-bottom: 20px; font-weight: 600; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <img src="../adssu.png" alt="Logo">
        <h3 style="margin:10px 0; font-size:16px;">Sub Admin Portal</h3>
    </div>
    <a href="dashboard.php" class="<?= $current_page=='dashboard.php'?'active':'' ?>">Dashboard Overview</a>
    <a href="applicants.php" class="<?= $current_page=='applicants.php'?'active':'' ?>">Manage Applicants</a>
    <a href="members.php" class="<?= $current_page=='members.php'?'active':'' ?>">Manage Members</a>
    <a href="announcements.php" class="<?= $current_page=='announcements.php'?'active':'' ?>">Announcements</a>
    <a href="reports.php" class="<?= $current_page=='reports.php'?'active':'' ?>">Reports & Logs</a>
    <a href="monitoring.php" class="<?= $current_page=='monitoring.php'?'active':'' ?>">Monitoring</a>
    <a href="logout.php" style="margin-top:auto; color:#ffc107;">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['fullname']) ?>!</h1>
    </div>
