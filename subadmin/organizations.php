<?php
require_once 'admin_header.php';

$msg = '';
$error = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
            
            if ($name) {
                $stmt = $pdo->prepare("INSERT INTO organizations (name, description) VALUES (?, ?)");
                if ($stmt->execute([$name, $desc])) {
                    $msg = "Organization added successfully!";
                } else {
                    $error = "Failed to add organization.";
                }
            } else {
                $error = "Organization name is required.";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['org_id'];
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
            
            if ($name) {
                $stmt = $pdo->prepare("UPDATE organizations SET name = ?, description = ? WHERE id = ?");
                if ($stmt->execute([$name, $desc, $id])) {
                    $msg = "Organization updated successfully!";
                } else {
                    $error = "Failed to update organization.";
                }
            } else {
                $error = "Organization name is required.";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['org_id'];
            $stmt = $pdo->prepare("DELETE FROM organizations WHERE id = ?");
            if ($stmt->execute([$id])) {
                $msg = "Organization deleted successfully!";
            } else {
                $error = "Failed to delete organization.";
            }
        }
    }
}
?>

<div class="card" style="margin-bottom: 30px;">
    <div class="card-title">Manage Organizations</div>
    <p>Add, edit, or remove student organizations available in the system.</p>
    
    <?php if($msg): ?><div class="msg" style="background:#d1e7dd; color:#0f5132; padding:15px; border-radius:5px; margin-bottom:15px;"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if($error): ?><div class="error" style="background:#f8d7da; color:#842029; padding:15px; border-radius:5px; margin-bottom:15px;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <!-- Add New Organization Form -->
    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #e9ecef;">
        <h3 style="margin-top:0; color:#1e3a2f; font-size:16px;">Add New Organization</h3>
        <form method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-start;">
            <input type="hidden" name="action" value="add">
            <div style="flex: 1; min-width: 250px;">
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Organization Name</label>
                <input type="text" name="name" required style="width:100%; padding:10px; border:1px solid #ced4da; border-radius:5px;" placeholder="e.g. College of Computing Studies">
            </div>
            <div style="flex: 2; min-width: 300px;">
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Description</label>
                <input type="text" name="description" style="width:100%; padding:10px; border:1px solid #ced4da; border-radius:5px;" placeholder="Brief description of the organization">
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding:10px 20px; margin-bottom:15px;">Add Organization</button>
            </div>
        </form>
    </div>

    <!-- List of Organizations -->
    <table>
        <tr>
            <th>ID</th>
            <th>Organization Name</th>
            <th>Description</th>
            <th style="width:180px;">Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM organizations ORDER BY id DESC");
        while($org = $stmt->fetch()):
        ?>
        <tr>
            <td><?= htmlspecialchars($org['id']) ?></td>
            <td><strong><?= htmlspecialchars($org['name']) ?></strong></td>
            <td><?= htmlspecialchars($org['description']) ?></td>
            <td>
                <!-- Edit Button triggers a simple JS prompt for demonstration, normally would use a modal -->
                <button onclick="editOrg(<?= $org['id'] ?>, '<?= addslashes(htmlspecialchars($org['name'])) ?>', '<?= addslashes(htmlspecialchars($org['description'])) ?>')" class="btn" style="background:#ffc107; color:#212529; font-size:12px; padding:5px 10px;">Edit</button>
                
                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this organization? All student applications tied to it will also be deleted!');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="org_id" value="<?= $org['id'] ?>">
                    <button type="submit" class="btn btn-danger" style="font-size:12px; padding:5px 10px;">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Hidden Edit Form -->
<form id="editForm" method="POST" style="display:none;">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="org_id" id="edit_org_id">
    <input type="hidden" name="name" id="edit_name">
    <input type="hidden" name="description" id="edit_description">
</form>

<script>
function editOrg(id, currentName, currentDesc) {
    let newName = prompt("Edit Organization Name:", currentName);
    if (newName !== null && newName.trim() !== "") {
        let newDesc = prompt("Edit Description:", currentDesc);
        if (newDesc !== null) {
            document.getElementById('edit_org_id').value = id;
            document.getElementById('edit_name').value = newName;
            document.getElementById('edit_description').value = newDesc;
            document.getElementById('editForm').submit();
        }
    }
}
</script>

<?php require_once 'admin_footer.php'; ?>
