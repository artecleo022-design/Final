<?php
require_once 'admin_header.php';

// Quick Stats
$stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'PENDING'");
$pending = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'APPROVED'");
$approved = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'");
$totalStudents = $stmt->fetchColumn();

// Organization Stats
$stmtOrgs = $pdo->query("
    SELECT o.name, COUNT(a.id) as member_count 
    FROM organizations o 
    LEFT JOIN applications a ON o.id = a.org_id AND a.status = 'APPROVED'
    GROUP BY o.id 
    ORDER BY member_count DESC
");
$orgStats = $stmtOrgs->fetchAll();
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 30px;">
    
    <div class="card" style="margin-bottom: 0; padding: 25px; border-left: 5px solid #FFC107; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 14px; font-weight: 800; color: #6c757d; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Pending Applications</div>
            <div style="font-size: 36px; font-weight: 800; color: var(--text-main);"><?= $pending ?></div>
        </div>
        <div style="width: 60px; height: 60px; border-radius: 16px; background: rgba(255,193,7,0.15); color: #FFC107; font-size: 28px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
    </div>
    
    <div class="card" style="margin-bottom: 0; padding: 25px; border-left: 5px solid #198754; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 14px; font-weight: 800; color: #6c757d; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Approved Members</div>
            <div style="font-size: 36px; font-weight: 800; color: var(--text-main);"><?= $approved ?></div>
        </div>
        <div style="width: 60px; height: 60px; border-radius: 16px; background: rgba(25,135,84,0.15); color: #198754; font-size: 28px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-user-check"></i>
        </div>
    </div>

    <div class="card" style="margin-bottom: 0; padding: 25px; border-left: 5px solid var(--primary); display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 14px; font-weight: 800; color: #6c757d; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Total Students</div>
            <div style="font-size: 36px; font-weight: 800; color: var(--text-main);"><?= $totalStudents ?></div>
        </div>
        <div style="width: 60px; height: 60px; border-radius: 16px; background: rgba(30,58,47,0.1); color: var(--primary); font-size: 28px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
    </div>

</div>

<div style="display: flex; gap: 25px; flex-wrap: wrap;">
    <div class="card" style="flex: 2; min-width: 400px; margin-bottom: 0;">
        <div class="card-title"><i class="fa-solid fa-sitemap"></i> Organization Membership</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Organization Name</th>
                        <th style="text-align: center;">Members</th>
                        <th>Popularity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orgStats as $stat): 
                        $percentage = $totalStudents > 0 ? ($stat['member_count'] / $totalStudents) * 100 : 0;
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($stat['name']) ?></strong></td>
                        <td style="text-align: center;"><?= $stat['member_count'] ?></td>
                        <td>
                            <div style="width: 100%; background: #e9ecef; border-radius: 10px; height: 8px;">
                                <div style="width: <?= $percentage ?>%; background: var(--primary); height: 100%; border-radius: 10px;"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 280px; text-align: center; display: flex; flex-direction: column; justify-content: center; background: linear-gradient(135deg, #0F5132 0%, #1e3a2f 100%); color: white;">
        <i class="fa-solid fa-award" style="font-size: 60px; color: var(--secondary); margin-bottom: 20px;"></i>
        <h2 style="color: white; margin: 0;">Top Organization</h2>
        <h3 style="color: var(--secondary); margin: 10px 0;"><?= !empty($orgStats) ? htmlspecialchars($orgStats[0]['name']) : 'None' ?></h3>
        <p style="font-size: 14px; opacity: 0.8;">Has the highest number of approved members.</p>
    </div>
</div>

<?php require_once 'admin_footer.php'; ?>
