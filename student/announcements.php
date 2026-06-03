<?php
require_once 'student_header.php';

// Get student's current organization
$stmtApp = $pdo->prepare("SELECT org_id FROM applications WHERE student_id = ? AND status = 'APPROVED'");
$stmtApp->execute([$user_id]);
$membership = $stmtApp->fetch();
$myOrgId = $membership ? $membership['org_id'] : 0;
?>

<div class="card" style="background: transparent; box-shadow: none; border: none; padding: 0;">
    <div class="card-title"><i class="fa-solid fa-bullhorn"></i> System Announcements</div>
    
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <?php
        // Show announcements targeting the student's org OR general ones (org_id IS NULL)
        $stmt = $pdo->prepare("
            SELECT * FROM announcements 
            WHERE org_id IS NULL OR org_id = ? 
            ORDER BY created_at DESC");
        $stmt->execute([$myOrgId]);
        $announcements = $stmt->fetchAll();
        if(count($announcements) > 0):
            foreach($announcements as $ann):
        ?>
            <div class="card" style="margin: 0; border-left: 5px solid var(--primary); padding: 25px; transition: transform 0.3s; position: relative; overflow: hidden;">
                <!-- Decorative Icon -->
                <i class="fa-solid fa-quote-right" style="position: absolute; right: 20px; top: 20px; font-size: 60px; color: var(--primary); opacity: 0.05;"></i>
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                    <div style="font-weight: 800; font-size: 20px; color: #2b3a4a;">
                        <?= htmlspecialchars($ann['title']) ?>
                    </div>
                    <span style="background: var(--primary-gradient); color: white; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 10px var(--primary-glow);">
                        <?= htmlspecialchars($ann['badge']) ?>
                    </span>
                </div>
                
                <div style="font-size: 13px; color: #6c757d; margin-bottom: 15px; display: flex; align-items: center;">
                    <i class="fa-regular fa-clock" style="margin-right: 5px; color: var(--primary);"></i> <?= date('F d, Y \a\t h:i A', strtotime($ann['created_at'])) ?>
                </div>
                
                <div style="font-size: 15px; color: #4a5568; line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($ann['content'])) ?>
                </div>
            </div>
        <?php 
            endforeach;
        else: ?>
            <div class="card" style="text-align: center; padding: 50px;">
                <i class="fa-regular fa-bell-slash" style="font-size: 50px; color: #cbd5e1; margin-bottom: 20px;"></i>
                <h3 style="color: #64748b; margin: 0;">No Announcements Yet</h3>
                <p style="color: #94a3b8; font-size: 15px;">Check back later for updates from the administration.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'student_footer.php'; ?>
