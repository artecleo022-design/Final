<?php
session_name('subadmin_portal');
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sub_admin') {
    die("Access Denied.");
}

$type = $_GET['type'] ?? 'all';
$academic_year = $_GET['academic_year'] ?? '';

$title = "Report";
$columns = [];
$data = [];

if (in_array($type, ['all', 'approved', 'rejected'])) {
    $whereClause = "";
    $params = [];
    
    if ($type === 'approved') {
        $whereClause = "WHERE a.status = 'APPROVED'";
        $title = "Approved Applicants List";
    } elseif ($type === 'rejected') {
        $whereClause = "WHERE a.status = 'REJECTED'";
        $title = "Rejected Applicants List";
    } else {
        $title = "Complete Applicant List";
    }
    
    if (!empty($academic_year)) {
        $whereClause .= ($whereClause === "" ? "WHERE " : " AND ") . "a.academic_year = ?";
        $params[] = $academic_year;
    }
    
    $stmt = $pdo->prepare("
        SELECT u.fullname, u.course, o.name as org_name, a.academic_year, a.status, a.applied_at 
        FROM applications a
        JOIN users u ON a.student_id = u.id
        JOIN organizations o ON a.org_id = o.id
        $whereClause
        ORDER BY a.academic_year DESC, u.fullname ASC
    ");
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columns = ['Student Name', 'Course', 'Organization', 'Year of Applying', 'Status', 'Date Applied'];
    
} elseif ($type === 'logins') {
    $title = "System Login History";
    // Logins ignore academic year
    $stmt = $pdo->query("
        SELECT u.fullname, u.role, l.login_time, l.logout_time, l.status
        FROM login_logs l
        JOIN users u ON u.id = l.user_id
        ORDER BY l.login_time DESC
        LIMIT 200
    ");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columns = ['User Fullname', 'Role', 'Login Time', 'Logout Time', 'Status'];
    
} elseif ($type === 'statistics') {
    $title = "Organization Statistics Report";
    $stmt = $pdo->query("
        SELECT o.name as org_name, 
               COUNT(a.id) as total,
               SUM(CASE WHEN a.status='APPROVED' THEN 1 ELSE 0 END) as approved,
               SUM(CASE WHEN a.status='PENDING' THEN 1 ELSE 0 END) as pending,
               SUM(CASE WHEN a.status='REJECTED' THEN 1 ELSE 0 END) as rejected
        FROM organizations o
        LEFT JOIN applications a ON o.id = a.org_id
        GROUP BY o.id
    ");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columns = ['Organization Name', 'Total Applicants', 'Approved', 'Pending', 'Rejected'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0F5132; padding-bottom: 10px; }
        .logo { width: 80px; height: auto; }
        .title { font-size: 24px; font-weight: bold; color: #0F5132; margin-top: 10px; text-transform: uppercase; }
        .subtitle { font-size: 14px; color: #666; margin-top: 5px; }
        
        .folder-header {
            background-color: #0F5132;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 16px;
            margin-top: 30px;
            border-radius: 6px 6px 0 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 0; font-size: 12px; margin-bottom: 20px; }
        .single-table { margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f1f3f5; color: #333; font-weight: bold; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .group-header { background-color: #f8f9fa; font-weight: bold; text-align: center; border-top: 2px solid #dee2e6; }
        
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #888; }
        
        @media print {
            body { padding: 0; }
            @page { margin: 1cm; }
            .folder-header { page-break-after: avoid; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <img src="../adssu.png" alt="Logo" class="logo">
    <div class="title"><?= htmlspecialchars($title) ?></div>
    <div class="subtitle">ADSSU Student Organizations System</div>
    <?php if(!empty($academic_year) && in_array($type, ['all', 'approved', 'rejected'])): ?>
    <div class="subtitle" style="font-weight:bold;">Year of Applying: <?= htmlspecialchars($academic_year) ?></div>
    <?php endif; ?>
    <div class="subtitle">Generated on: <?= date('F d, Y h:i A') ?></div>
</div>

<?php if (in_array($type, ['all', 'approved', 'rejected']) && empty($academic_year)): ?>
    <?php 
    // Group data by year
    $groupedData = [];
    foreach($data as $row) {
        $year = $row['academic_year'] ?? 'Unknown';
        $groupedData[$year][] = $row;
    }
    
    if (empty($groupedData)): ?>
        <table class="single-table">
            <thead><tr><?php foreach($columns as $col): ?><th><?= htmlspecialchars($col) ?></th><?php endforeach; ?></tr></thead>
            <tbody><tr><td colspan="<?= count($columns) ?>" style="text-align:center; padding: 20px;">No records found.</td></tr></tbody>
        </table>
    <?php else: ?>
        <?php foreach($groupedData as $year => $rows): ?>
            <div class="folder-header">📁 Year of Applying: <?= htmlspecialchars($year) ?></div>
            <table>
                <thead>
                    <tr>
                        <?php foreach($columns as $col): ?>
                        <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['fullname']) ?></strong></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td><?= htmlspecialchars($row['org_name']) ?></td>
                        <td><?= htmlspecialchars($row['academic_year']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= date('M d, Y', strtotime($row['applied_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>

<?php else: ?>
    <table class="single-table">
        <thead>
            <tr>
                <?php foreach($columns as $col): ?>
                <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $row): ?>
            <tr>
                <?php if(in_array($type, ['all', 'approved', 'rejected'])): ?>
                    <td><strong><?= htmlspecialchars($row['fullname']) ?></strong></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['org_name']) ?></td>
                    <td><?= htmlspecialchars($row['academic_year']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= date('M d, Y', strtotime($row['applied_at'])) ?></td>
                <?php elseif($type === 'logins'): ?>
                    <td><strong><?= htmlspecialchars($row['fullname']) ?></strong></td>
                    <td style="text-transform:capitalize;"><?= htmlspecialchars(str_replace('_', ' ', $row['role'])) ?></td>
                    <td><?= date('M d, Y h:i A', strtotime($row['login_time'])) ?></td>
                    <td><?= $row['logout_time'] ? date('M d, Y h:i A', strtotime($row['logout_time'])) : '-' ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                <?php elseif($type === 'statistics'): ?>
                    <td><strong><?= htmlspecialchars($row['org_name']) ?></strong></td>
                    <td><?= $row['total'] ?></td>
                    <td><?= $row['approved'] ?></td>
                    <td><?= $row['pending'] ?></td>
                    <td><?= $row['rejected'] ?></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            <?php if(count($data) === 0): ?>
            <tr><td colspan="<?= count($columns) ?>" style="text-align:center; padding: 20px;">No records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="footer">
    &copy; <?= date('Y') ?> Agusan Del Sur State College of Agriculture and Technology. This is a system-generated report.
</div>

</body>
</html>
