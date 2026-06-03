<?php
require_once 'admin_header.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['announcement_id'];
        $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
        if ($stmt->execute([$id])) {
            $msg = "Announcement deleted successfully!";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $id = $_POST['announcement_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $badge = $_POST['badge'];
        $org_id = !empty($_POST['org_id']) ? $_POST['org_id'] : null;
        
        $stmt = $pdo->prepare("UPDATE announcements SET title = ?, content = ?, badge = ?, org_id = ? WHERE id = ?");
        if ($stmt->execute([$title, $content, $badge, $org_id, $id])) {
            $msg = "Announcement updated successfully!";
        }
    } elseif (isset($_POST['title'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $badge = $_POST['badge'];
        $org_id = !empty($_POST['org_id']) ? $_POST['org_id'] : null;
        
        $stmt = $pdo->prepare("INSERT INTO announcements (title, content, badge, org_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $content, $badge, $org_id])) {
            $msg = "Announcement posted successfully!";
        }
    }
}
?>

<div style="display: flex; gap: 30px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 300px;">
        <div class="card" style="position: sticky; top: 20px;">
            <div class="card-title"><i class="fa-solid fa-pen-to-square"></i> Post Announcement</div>
            <?php if($msg): ?>
                <div class="msg"><i class="fa-solid fa-circle-check"></i><div><?= htmlspecialchars($msg) ?></div></div>
            <?php endif; ?>
            
            <form method="POST">
                <label>Title</label>
                <input type="text" name="title" required placeholder="e.g. System Maintenance">
                
                <label>Badge Type</label>
                <div style="position: relative;">
                    <select name="badge" required style="appearance: none;">
                        <option value="GENERAL">GENERAL</option>
                        <option value="URGENT">URGENT</option>
                        <option value="INFO">INFO</option>
                        <option value="EVENT">EVENT</option>
                    </select>
                    <i class="fa-solid fa-chevron-down" style="position: absolute; right: 20px; top: 18px; color: #a0aec0; pointer-events: none;"></i>
                </div>
                
                <label>Target Organization</label>
                <div style="position: relative;">
                    <select name="org_id" style="appearance: none;">
                        <option value="">ALL ORGANIZATIONS (General)</option>
                        <?php
                        $orgs = $pdo->query("SELECT id, name FROM organizations ORDER BY name ASC")->fetchAll();
                        foreach($orgs as $org): ?>
                            <option value="<?= $org['id'] ?>"><?= htmlspecialchars($org['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fa-solid fa-chevron-down" style="position: absolute; right: 20px; top: 18px; color: #a0aec0; pointer-events: none;"></i>
                </div>

                <label>Content</label>
                <textarea name="content" rows="5" required placeholder="Write your announcement here..."></textarea>
                
                <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-paper-plane"></i> Publish</button>
            </form>
        </div>
    </div>
    
    <div style="flex: 2; min-width: 350px;">
        <div class="card">
            <div class="card-title"><i class="fa-solid fa-clock-rotate-left"></i> History</div>
            <div style="overflow-x: auto;">
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Badge</th>
                        <th>Target Organization</th>
                        <th>Title & Preview</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                    <?php
                    $stmt = $pdo->query("
                        SELECT a.*, o.name as target_org 
                        FROM announcements a 
                        LEFT JOIN organizations o ON a.org_id = o.id 
                        ORDER BY a.created_at DESC");
                    while($ann = $stmt->fetch()):
                        $badgeColors = ['GENERAL' => '#6c757d', 'URGENT' => '#dc3545', 'INFO' => '#0d6efd', 'EVENT' => '#6f42c1'];
                        $bColor = $badgeColors[$ann['badge']] ?? '#1e3a2f';
                    ?>
                    <tr>
                        <td style="white-space: nowrap; font-size: 13px; color: #6c757d;"><i class="fa-regular fa-calendar" style="margin-right: 5px;"></i><?= date('M d, Y', strtotime($ann['created_at'])) ?></td>
                        <td><span style="background: <?= $bColor ?>; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; letter-spacing: 1px;"><?= htmlspecialchars($ann['badge']) ?></span></td>
                        <td style="font-size: 12px; color: #4a5568;">
                            Target: <strong><?= $ann['target_org'] ? htmlspecialchars($ann['target_org']) : 'All' ?></strong>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: var(--text-main); margin-bottom: 5px;"><?= htmlspecialchars($ann['title']) ?></div>
                            <div style="font-size: 13px; color: #6c757d; line-height: 1.4;"><?= htmlspecialchars(substr($ann['content'], 0, 60)) ?>...</div>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <button onclick="editAnn(<?= $ann['id'] ?>, '<?= addslashes(htmlspecialchars($ann['title'])) ?>', '<?= addslashes(htmlspecialchars($ann['content'])) ?>', '<?= $ann['badge'] ?>', '<?= $ann['org_id'] ?>')" class="btn btn-warning" style="padding: 6px 12px; font-size: 12px;"><i class="fa-solid fa-pen"></i></button>
                            
                            <form method="POST" style="display:inline-block; margin:0;" onsubmit="return confirm('Delete this announcement?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="announcement_id" value="<?= $ann['id'] ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<form id="editForm" method="POST" style="display:none;">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="announcement_id" id="edit_ann_id">
    <input type="hidden" name="title" id="edit_title">
    <input type="hidden" name="content" id="edit_content">
    <input type="hidden" name="badge" id="edit_badge">
    <input type="hidden" name="org_id" id="edit_org_id_field">
</form>

<script>
function editAnn(id, currentTitle, currentContent, currentBadge, currentOrgId) {
    let newTitle = prompt("Edit Title:", currentTitle);
    if (newTitle !== null && newTitle.trim() !== "") {
        let newBadge = prompt("Edit Badge (GENERAL, URGENT, INFO, EVENT):", currentBadge);
        if (newBadge !== null && newBadge.trim() !== "") {
            let newContent = prompt("Edit Content:", currentContent);
            if (newContent !== null) {
                let newOrg = prompt("Edit Org ID (leave blank for All Organizations):", currentOrgId || "");
                document.getElementById('edit_ann_id').value = id;
                document.getElementById('edit_title').value = newTitle;
                document.getElementById('edit_org_id_field').value = newOrg;
                document.getElementById('edit_badge').value = newBadge.toUpperCase();
                document.getElementById('edit_content').value = newContent;
                document.getElementById('editForm').submit();
            }
        }
    }
}
</script>

<?php require_once 'admin_footer.php'; ?>
