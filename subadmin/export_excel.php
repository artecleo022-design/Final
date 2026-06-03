<?php
session_name('subadmin_portal');
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sub_admin') {
    die("Access Denied.");
}

$filename = "Active_Students_Report_" . date('Ymd') . ".xls";

// Set header to force download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

$academic_year = $_GET['academic_year'] ?? '';
$whereClause = "WHERE a.status = 'APPROVED'";
$params = [];

if (!empty($academic_year)) {
    $whereClause .= " AND a.academic_year = ?";
    $params[] = $academic_year;
    $filename = "Active_Students_" . preg_replace('/[^a-zA-Z0-9_-]/', '_', $academic_year) . "_" . date('Ymd') . ".xls";
    // Need to set headers again if filename changes
    header("Content-Disposition: attachment; filename=\"$filename\"");
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

// Output HTML table for Excel with inline CSS for design
echo '
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="ProgId" content="Excel.Sheet"/>
<meta name="Generator" content="Microsoft Excel"/>
<title>Active Students Report</title>
<meta charset="utf-8">
<style>
    .table-container { font-family: Arial, sans-serif; }
    .title { font-size: 20px; font-weight: bold; color: #0F5132; text-align: center; margin-bottom: 15px; }
    table { border-collapse: collapse; width: 100%; }
    th { background-color: #0F5132; color: #ffffff; font-weight: bold; padding: 10px; border: 1px solid #dddddd; text-align: left; }
    td { padding: 8px; border: 1px solid #dddddd; color: #333333; }
    .row-even { background-color: #f9f9f9; }
    .row-odd { background-color: #ffffff; }
</style>
</head>
<body>

<div class="table-container">
    <div class="header" style="text-align: center; margin-bottom: 20px;">
        <img src="../adssu.png" alt="Logo" style="width: 80px; height: auto; margin-bottom: 10px;">
        <div class="title">Active Students Report ' . (!empty($academic_year) ? ' - A.Y. ' . htmlspecialchars($academic_year) : '') . '</div>
        <div class="subtitle" style="font-size: 14px; color: #666; margin-top: 5px;">ADSSU Student Organizations System</div>
        ' . (!empty($academic_year) ? '<div class="subtitle" style="font-weight:bold;">Year of Applying: ' . htmlspecialchars($academic_year) . '</div>' : '') . '
        <div class="subtitle" style="font-size: 12px; color: #888; margin-top: 5px;">Generated on: ' . date('F d, Y h:i A') . '</div>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Course</th>
                <th>Email</th>
                <th>Organization</th>
                <th>Year of Applying</th>
                <th>Approved Date</th>
            </tr>
        </thead>
        <tbody>
';

$rowNum = 0;
$currentYear = '';
while ($row = $stmt->fetch()) {
    if (empty($academic_year) && $currentYear !== $row['academic_year']) {
        $currentYear = $row['academic_year'];
        echo '<tr style="background-color: #e9ecef; font-weight: bold;"><td colspan="6" style="text-align: center; padding: 10px;">Year of Applying: ' . htmlspecialchars($currentYear) . '</td></tr>';
        $rowNum = 0; // Reset striping for new group
    }
    $rowClass = ($rowNum % 2 == 0) ? 'row-even' : 'row-odd';
    echo '<tr class="' . $rowClass . '">';
    echo '<td>' . htmlspecialchars($row['fullname']) . '</td>';
    echo '<td>' . htmlspecialchars($row['course']) . '</td>';
    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
    echo '<td>' . htmlspecialchars($row['org_name']) . '</td>';
    echo '<td>' . htmlspecialchars($row['academic_year']) . '</td>';
    echo '<td>' . date('M d, Y', strtotime($row['applied_at'])) . '</td>';
    echo '</tr>';
    $rowNum++;
}

echo '
        </tbody>
    </table>
</div>
</body>
</html>
';
?>
