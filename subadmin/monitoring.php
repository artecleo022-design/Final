<?php
require_once 'admin_header.php';
?>

<div class="card">
    <div class="card-title" style="margin-bottom: 5px;"><i class="fa-solid fa-desktop"></i> Login Monitoring</div>
    <p style="color: #6c757d; margin-bottom: 25px; font-size: 14px;">Track user authentication events and login timestamps.</p>
    
    <div style="overflow-x: auto;">
        <table>
            <tr>
                <th>Log ID</th>
                <th>User Fullname</th>
                <th>Role</th>
                <th>Login Time</th>
                <th>Status</th>
            </tr>
            <?php
            $stmt = $pdo->query("SELECT l.id, u.fullname, u.role, l.login_time, l.status 
                                 FROM login_logs l 
                                 JOIN users u ON l.user_id = u.id 
                                 ORDER BY l.login_time DESC LIMIT 100");
            while($log = $stmt->fetch()):
            ?>
            <tr>
                <td style="font-size: 13px; color: #a0aec0;">#<?= str_pad($log['id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td><strong><?= htmlspecialchars($log['fullname']) ?></strong></td>
                <td>
                    <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: <?= $log['role'] === 'sub_admin' ? 'var(--secondary)' : '#6c757d' ?>;">
                        <?= htmlspecialchars(str_replace('_', ' ', $log['role'])) ?>
                    </span>
                </td>
                <td style="font-size: 13px; color: #4a5568;"><i class="fa-regular fa-clock" style="margin-right: 5px; color: var(--primary);"></i><?= date('F d, Y h:i A', strtotime($log['login_time'])) ?></td>
                <td>
                    <span style="background: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                        <i class="fa-solid fa-circle" style="font-size: 8px; margin-right: 4px; vertical-align: middle;"></i><?= htmlspecialchars($log['status']) ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php require_once 'admin_footer.php'; ?>
