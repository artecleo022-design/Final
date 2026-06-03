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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Student Portal | ADSSU</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
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
            min-height: 100vh;
        }
        .layout-wrapper { display: flex; min-height: 100vh; flex-direction: column; }
        @media (min-width: 768px) { .layout-wrapper { flex-direction: row; } }

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
            flex: 1; padding: 20px; min-width: 0; width: 100%;
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

        .btn {
            padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;
            font-weight: 600; text-decoration: none; display: inline-block;
            font-family: inherit; font-size: 14px; transition: 0.3s;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #157347; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #bb2d3b; }
        .btn-disabled { background: #e2e8f0; color: #a0aec0; cursor: not-allowed; }

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
        .msg-error { background: #f8d7da; color: #842029; }

        label { display: block; font-weight: 600; margin-bottom: 5px; color: #555; font-size: 14px; }

        .grid-2 { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media (min-width: 640px) { .grid-2 { grid-template-columns: 1fr 1fr; } }
        .grid-3 { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media (min-width: 640px) { .grid-3 { grid-template-columns: 1fr 1fr; } }
        @media (min-width: 1024px) { .grid-3 { grid-template-columns: 1fr 1fr 1fr; } }

        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 0 -5px; }
        .table-wrap table { min-width: 600px; }

        @media (max-width: 767px) {
            .sidebar a { font-size: 13px; padding: 12px 16px; }
            .sidebar-logo img { width: 55px; }
            .sidebar-logo h3 { font-size: 14px; }
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<div class="sidebar-toggle">
    <div class="logo-row">
        <img src="../adssu.png" alt="Logo">
        <h3>Student Portal</h3>
    </div>
    <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle menu">&#9776;</button>
</div>
<div class="layout-wrapper">
    <div class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="../adssu.png" alt="Logo">
            <h3 style="margin:10px 0; font-size:16px;">Student Portal</h3>
        </div>
        <a href="dashboard.php" class="<?= $current_page=='dashboard.php'?'active':'' ?>">Dashboard</a>
        <a href="organizations.php" class="<?= $current_page=='organizations.php'?'active':'' ?>">Organizations</a>
        <a href="announcements.php" class="<?= $current_page=='announcements.php'?'active':'' ?>">Announcements</a>
        <a href="profile.php" class="<?= $current_page=='profile.php'?'active':'' ?>">Edit Profile</a>
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
