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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
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
            --sidebar-width: 250px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0; font-family: 'Outfit', sans-serif; background: var(--bg-color); color: var(--text-main);
            min-height: 100vh; display: flex; flex-direction: column;
        }
        .app-wrap { display: flex; flex: 1; min-height: 0; }
        @media (min-width: 768px) { .app-wrap { flex-direction: row; } }

        .sidebar-toggle {
            display: flex; align-items: center; justify-content: space-between;
            background: #1e3a2f; color: white; padding: 12px 20px;
            position: sticky; top: 0; z-index: 100;
        }
        @media (min-width: 768px) { .sidebar-toggle { display: none; } }
        .sidebar-toggle .logo-row { display: flex; align-items: center; gap: 10px; }
        .sidebar-toggle img { width: 36px; height: auto; }
        .sidebar-toggle h3 { margin: 0; font-size: 15px; }
        .hamburger {
            background: none; border: none; color: white; font-size: 26px;
            cursor: pointer; padding: 4px 8px; border-radius: 6px;
        }
        .hamburger:hover { background: rgba(255,255,255,0.1); }

        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4);
            z-index: 98;
        }
        .sidebar-overlay.show { display: block; }

        .sidebar {
            width: var(--sidebar-width); background: #1e3a2f; color: white;
            display: flex; flex-direction: column; flex-shrink: 0;
            position: fixed; top: 0; left: calc(var(--sidebar-width) * -1);
            height: 100vh; z-index: 99; transition: left 0.3s ease;
            overflow-y: auto;
        }
        .sidebar.open { left: 0; }
        @media (min-width: 768px) {
            .sidebar { position: sticky; top: 0; left: 0; height: 100vh; }
        }

        .sidebar-logo { text-align: center; margin-bottom: 20px; padding-top: 20px; }
        .sidebar-logo img { width: 70px; }
        .sidebar a {
            color: #adb5bd; text-decoration: none; padding: 14px 20px; display: block;
            font-weight: 600; transition: 0.3s; font-size: 14px;
        }
        .sidebar a:hover, .sidebar a.active { background: var(--secondary); color: #1e3a2f; border-left: 4px solid white; }

        .main-content {
            flex: 1; padding: 20px; overflow-y: auto; min-width: 0; width: 100%;
        }
        @media (min-width: 768px) { .main-content { padding: 30px; } }

        .header { margin-bottom: 20px; }
        .header h1 { margin: 0; color: var(--primary); font-weight: 800; font-size: clamp(20px, 4vw, 32px); }

        .card {
            background: var(--card-bg); border-radius: 10px; padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px;
        }
        @media (min-width: 768px) { .card { padding: 25px; } }
        .card-title { font-size: clamp(16px, 3vw, 20px); font-weight: 800; color: var(--primary); margin-bottom: 15px; }

        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 0 -5px; }
        .table-wrap table { min-width: 600px; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #dee2e6; font-size: 13px; }
        @media (min-width: 768px) { th, td { padding: 12px; font-size: 14px; } }
        th { background: #f8f9fa; color: #495057; font-weight: 800; text-transform: uppercase; font-size: 11px; }
        @media (min-width: 768px) { th { font-size: 12px; } }

        .btn {
            padding: 8px 14px; border: none; border-radius: 5px; cursor: pointer;
            font-weight: 600; text-decoration: none; display: inline-block;
            font-family: inherit; font-size: 13px; transition: 0.3s;
        }
        .btn-success { background: var(--primary); color: white; }
        .btn-success:hover { background: #157347; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #bb2d3b; }
        .btn-primary { background: var(--primary); color: white; }

        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px; }
        .status-PENDING { background: #ffeeba; color: #856404; }
        .status-APPROVED { background: #d4edda; color: #155724; }
        .status-REJECTED { background: #f8d7da; color: #721c24; }

        input, select, textarea {
            width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ced4da;
            border-radius: 5px; box-sizing: border-box; font-family: inherit; font-size: 15px;
        }
        .msg {
            padding: 12px 15px; border-radius: 5px; background: #d1e7dd; color: #0f5132;
            margin-bottom: 20px; font-weight: 600; font-size: 14px;
        }

        .stat-cards { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media (min-width: 640px) { .stat-cards { grid-template-columns: 1fr 1fr; } }
        @media (min-width: 1024px) { .stat-cards { grid-template-columns: 1fr 1fr 1fr 1fr; } }

        .grid-2 { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media (min-width: 640px) { .grid-2 { grid-template-columns: 1fr 1fr; } }

        @media (max-width: 767px) {
            .sidebar a { font-size: 13px; padding: 12px 16px; }
            .sidebar-logo img { width: 55px; }
            .sidebar-logo h3 { font-size: 14px; }
        }

        .stat-box {
            background: white; border-radius: 10px; padding: 20px; text-align: center;
            border: 1px solid #edf2f7;
        }
        .stat-box .num { font-size: clamp(28px, 5vw, 42px); font-weight: 800; color: var(--primary); }
        .stat-box .label { font-size: 13px; color: #6c757d; font-weight: 600; margin-top: 4px; }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<div class="sidebar-toggle">
    <div class="logo-row">
        <img src="../adssu.png" alt="Logo">
        <h3>Sub Admin Portal</h3>
    </div>
    <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle menu">&#9776;</button>
</div>
<div class="app-wrap">
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img src="../adssu.png" alt="Logo">
        <h3 style="margin:10px 0; font-size:16px;">Sub Admin Portal</h3>
    </div>
    <a href="dashboard.php" class="<?= $current_page=='dashboard.php'?'active':'' ?>">Dashboard Overview</a>
    <a href="applicants.php" class="<?= $current_page=='applicants.php'?'active':'' ?>">Manage Applicants</a>
    <a href="organizations.php" class="<?= $current_page=='organizations.php'?'active':'' ?>">Organizations</a>
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

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
document.addEventListener('click', function(e) {
    var sidebar = document.getElementById('sidebar');
    var toggle = document.querySelector('.sidebar-toggle');
    if (window.innerWidth < 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }
});
</script>
