<?php
require_once 'student_header.php';

// Get all student's applications
$stmt = $pdo->prepare("
    SELECT a.status, a.academic_year, o.name as org_name, o.id as org_id
    FROM applications a 
    JOIN organizations o ON a.org_id = o.id 
    WHERE a.student_id = ?
    ORDER BY a.applied_at DESC
");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();

// Get count of organizations
$stmtOrgs = $pdo->query("SELECT COUNT(*) FROM organizations");
$orgCount = $stmtOrgs->fetchColumn();
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
    
    <!-- Membership Card -->
    <div class="card" style="border-top: 5px solid var(--primary);">
        <div class="card-title"><i class="fa-solid fa-id-card"></i> My Organizations</div>
        <?php if (count($applications) > 0): ?>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach ($applications as $app): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <div>
                            <strong style="color: var(--primary);"><?= htmlspecialchars($app['org_name']) ?></strong>
                            <div style="font-size: 12px; color: #6c757d; margin-top: 3px;">Year: <?= htmlspecialchars($app['academic_year']) ?></div>
                        </div>
                        <span class="status-badge status-<?= $app['status'] ?>"><?= $app['status'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px 20px;">
                <i class="fa-solid fa-user-slash" style="font-size: 50px; color: #dee2e6; margin-bottom: 20px;"></i>
                <p style="color: #6c757d;">You haven't joined any organization yet.</p>
                <a href="organizations.php" class="btn btn-primary">Browse Organizations</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Info Card -->
    <div class="card" style="border-top: 5px solid var(--secondary); background: #fffcf0;">
        <div class="card-title"><i class="fa-solid fa-circle-info"></i> Campus Activity</div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 20px;">
            <div>
                <div style="font-size: 14px; color: #6c757d; font-weight: 600;">Active Organizations</div>
                <div style="font-size: 48px; font-weight: 800; color: #1e3a2f;"><?= $orgCount ?></div>
            </div>
            <i class="fa-solid fa-sitemap" style="font-size: 60px; color: var(--secondary); opacity: 0.5;"></i>
        </div>
        <p style="font-size: 14px; color: #6c757d; margin-top: 20px;">
            Explore various student-led groups and build your community today!
        </p>
    </div>

</div>

<!-- Announcements Feed -->
<div class="card" style="margin-top: 25px;">
    <div class="card-title"><i class="fa-solid fa-bullhorn"></i> Latest Announcements</div>
    <div style="padding: 10px 0;">
        <p style="color: #6c757d; font-style: italic;">Check the <a href="announcements.php" style="color: var(--primary); font-weight: bold;">Announcements page</a> for detailed updates and events from your organization.</p>
    </div>
</div>

<?php require_once 'student_footer.php'; ?>