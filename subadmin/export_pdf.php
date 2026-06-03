<?php
session_name('subadmin_portal');
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sub_admin') {
    die("Access Denied.");
}

$academic_year = $_GET['academic_year'] ?? '';
$whereClause = "WHERE a.status = 'APPROVED'";
$params = [];

if (!empty($academic_year)) {
    $whereClause .= " AND a.academic_year = ?";
    $params[] = $academic_year;
}

$stmt = $pdo->prepare("
    SELECT u.fullname, u.course, u.email, o.name as org_name, a.academic_year, a.applied_at 
    FROM applications a
    JOIN users u ON a.student_id = u.id
    JOIN organizations o ON a.org_id = o.id
    $whereClause
    ORDER BY a.academic_year DESC, u.fullname ASC
");
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Active Students Report <?= !empty($academic_year) ? " - " . htmlspecialchars($academic_year) : "" ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0F5132; padding-bottom: 10px; }
        .logo { width: 80px; height: auto; }
        .title { font-size: 24px; font-weight: bold; color: #0F5132; margin-top: 10px; text-transform: uppercase; }
        .subtitle { font-size: 14px; color: #666; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #0F5132; color: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        tr:nth-child(even) { background-color: #f9f9f9; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .year-header { background-color: #e9ecef; font-weight: bold; text-align: center; color: #1e3a2f; }
        
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #888; }
        
        @media print {
            body { padding: 0; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <img src="../adssu.png" alt="Logo" class="logo">
    <div class="title">Active Students Report</div>
    <div class="subtitle">ADSSU Student Organizations System</div>
    <?php if(!empty($academic_year)): ?>
    <div class="subtitle" style="font-weight:bold;">Year of Applying: <?= htmlspecialchars($academic_year) ?></div>
    <?php endif; ?>
    <div class="subtitle">Generated on: <?= date('F d, Y h:i A') ?></div>
</div>

<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Course</th>
            <th>Email</th>
            <th>Organization</th>
            <th>A.Y.</th>
            <th>Approved Date</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $currentYear = '';
        foreach($students as $row): 
            if (empty($academic_year) && $currentYear !== $row['academic_year']):
                $currentYear = $row['academic_year'];
        ?>
        <tr class="year-header"><td colspan="6">Year of Applying: <?= htmlspecialchars($currentYear) ?></td></tr>
        <?php endif; ?>
        <tr>
            <td><strong><?= htmlspecialchars($row['fullname']) ?></strong></td>
            <td><?= htmlspecialchars($row['course']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['org_name']) ?></td>
            <td><?= htmlspecialchars($row['academic_year']) ?></td>
            <td><?= date('M d, Y', strtotime($row['applied_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if(count($students) === 0): ?>
        <tr><td colspan="6" style="text-align:center; padding: 20px;">No records found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="footer">
    &copy; <?= date('Y') ?> Agusan Del Sur State College of Agriculture and Technology. This is a system-generated report.
</div>

</body>
</html>
