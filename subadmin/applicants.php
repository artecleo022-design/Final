<?php
require_once 'admin_header.php';

$msg = '';
$msg_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $app_id = $_POST['app_id'];
    $action = $_POST['action']; // 'approve' or 'reject'
    
    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE applications SET status = 'APPROVED' WHERE id = ?");
        if ($stmt->execute([$app_id])) {
            $msg = "Application approved successfully.";
        }
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE applications SET status = 'REJECTED' WHERE id = ?");
        if ($stmt->execute([$app_id])) {
            $msg = "Application rejected.";
            $msg_type = 'error';
        }
    }
}
?>

<div class="card">
    <div class="card-title"><i class="fa-solid fa-users-viewfinder"></i> Manage Applicants</div>
    
    <?php if($msg): ?>
        <div class="msg <?= $msg_type === 'error' ? 'msg-error' : '' ?>">
            <i class="fa-solid <?= $msg_type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check' ?>"></i>
            <div><?= htmlspecialchars($msg) ?></div>
        </div>
    <?php endif; ?>
    
    <div style="overflow-x: auto;">
        <table>
            <tr>
                <th>Student Name</th>
                <th>Course</th>
                <th>Applied Organization</th>
                <th>Date Applied</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
            <?php
            $stmt = $pdo->query("SELECT a.id, u.fullname, u.course, o.name as org_name, a.status, a.applied_at 
                                 FROM applications a 
                                 JOIN users u ON a.student_id = u.id 
                                 JOIN organizations o ON a.org_id = o.id 
                                 ORDER BY a.applied_at DESC");
            while($row = $stmt->fetch()):
            ?>
            <tr>
                <td><strong><?= htmlspecialchars($row['fullname']) ?></strong></td>
                <td><span style="color: #6c757d; font-size: 14px;"><i class="fa-solid fa-graduation-cap" style="margin-right: 5px;"></i><?= htmlspecialchars($row['course']) ?></span></td>
                <td><?= htmlspecialchars($row['org_name']) ?></td>
                <td style="font-size: 13px; color: #4a5568;"><i class="fa-regular fa-calendar" style="margin-right: 5px;"></i><?= date('M d, Y', strtotime($row['applied_at'])) ?></td>
                <td><span class="status-badge status-<?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                <td style="text-align: right; white-space: nowrap;">
                    <?php if($row['status'] === 'PENDING'): ?>
                    <form method="POST" style="display:inline-block; margin:0;" onsubmit="return confirm('Approve this application?');">
                        <input type="hidden" name="action" value="approve">
                        <input type="hidden" name="app_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;"><i class="fa-solid fa-check"></i> Approve</button>
                    </form>
                    <form method="POST" style="display:inline-block; margin:0;" onsubmit="return confirm('Reject this application?');">
                        <input type="hidden" name="action" value="reject">
                        <input type="hidden" name="app_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;"><i class="fa-solid fa-xmark"></i> Reject</button>
                    </form>
                    <?php else: ?>
                        <span style="color: #a0aec0; font-size: 13px; font-weight: 600;"><i class="fa-solid fa-lock"></i> Processed</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php require_once 'admin_footer.php'; ?>
