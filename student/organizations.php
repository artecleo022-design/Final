<?php
require_once 'student_header.php';

// Load all applications for this student
$stmtApps = $pdo->prepare("SELECT org_id, status FROM applications WHERE student_id = ?");
$stmtApps->execute([$user_id]);
$myApps = [];
while ($row = $stmtApps->fetch()) {
    $myApps[$row['org_id']] = $row['status'];
}
?>

<div class="card" style="background: transparent; box-shadow: none; border: none; padding: 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div class="card-title" style="margin: 0;"><i class="fa-solid fa-layer-group"></i> Available Organizations</div>
        <span style="background: rgba(255,255,255,0.7); padding: 8px 15px; border-radius: 20px; font-size: 13px; font-weight: 800; color: #4a5568; box-shadow: 0 2px 10px rgba(0,0,0,0.02);"><i class="fa-solid fa-circle-info" style="color: var(--primary);"></i> You can apply to multiple organizations</span>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px;">
        <?php
        $stmt = $pdo->query("SELECT * FROM organizations");
        while($org = $stmt->fetch()):
            // Dynamic subtle color based on ID for grid variety
            $org_colors = ['#0d6efd', '#6f42c1', '#d39e00', '#198754', '#d63384', '#20c997', '#dc3545', '#fd7e14', '#6610f2', '#6c757d'];
            $accent = $org_colors[($org['id'] - 1) % 10];
        ?>
        <div class="card" style="margin: 0; padding: 30px; display: flex; flex-direction: column; border-top: 5px solid <?= $accent ?>;">
            <div style="width: 50px; height: 50px; background: <?= $accent ?>20; color: <?= $accent ?>; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">
                <i class="fa-solid fa-users-rectangle"></i>
            </div>
            <h3 style="color: #2b3a4a; margin: 0 0 10px 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;"><?= htmlspecialchars($org['name']) ?></h3>
            <p style="font-size:15px; color:#6c757d; line-height: 1.6; flex: 1; margin-top: 0;"><?= htmlspecialchars($org['description']) ?></p>
            
            <div style="margin-top: 25px;">
                <?php 
                $appStatus = isset($myApps[$org['id']]) ? $myApps[$org['id']] : null;
                if ($appStatus === 'APPROVED'): ?>
                    <button class="btn" style="width: 100%; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: #0d4a22; cursor: default; box-shadow: none;"><i class="fa-solid fa-check-double"></i> Approved Member</button>
                <?php elseif ($appStatus === 'PENDING'): ?>
                    <button class="btn" style="width: 100%; background: #fff3cd; color: #856404; cursor: default; box-shadow: none;"><i class="fa-solid fa-hourglass-half"></i> Pending Review</button>
                <?php else: ?>
                    <form action="apply.php" method="POST" style="margin:0;">
                        <input type="hidden" name="apply_org_id" value="<?= $org['id'] ?>">
                        <button type="submit" class="btn" style="width: 100%; background: <?= $accent ?>; color: white; box-shadow: 0 8px 20px <?= $accent ?>40; text-transform: uppercase; font-weight: 800;" onclick="return confirm('Apply to <?= addslashes($org['name']) ?>?');"><i class="fa-solid fa-paper-plane"></i> <?= $appStatus === 'REJECTED' ? 'Apply Again' : 'Apply Now' ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once 'student_footer.php'; ?>
