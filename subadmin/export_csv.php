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

$filename = "Active_Students_Report_" . date('Ymd') . ".csv";

if (!empty($academic_year)) {
    $whereClause .= " AND a.academic_year = ?";
    $params[] = $academic_year;
    $filename = "Active_Students_" . preg_replace('/[^a-zA-Z0-9_-]/', '_', $academic_year) . "_" . date('Ymd') . ".csv";
}

// Output headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen('php://output', 'w');

// Output CSV column headers
fputcsv($output, ['Student Name', 'Course', 'Email', 'Organization', 'Year of Applying', 'Approved Date']);

$stmt = $pdo->prepare("
    SELECT u.fullname, u.course, u.email, o.name as org_name, a.academic_year, a.applied_at 
    FROM applications a
    JOIN users u ON a.student_id = u.id
    JOIN organizations o ON a.org_id = o.id
    $whereClause
    ORDER BY a.academic_year DESC, u.fullname ASC
");
$stmt->execute($params);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['fullname'],
        $row['course'],
        $row['email'],
        $row['org_name'],
        $row['academic_year'],
        date('M d, Y', strtotime($row['applied_at']))
    ]);
}
fclose($output);
exit;
?>
