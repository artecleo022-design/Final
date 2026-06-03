<?php
require_once 'admin_header.php';

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete') {
            $id = $_POST['student_id'];
            // Ensure we only delete student roles for safety
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
            if ($stmt->execute([$id])) {
                $msg = "Student record deleted successfully!";
            } else {
                $error = "Failed to delete student.";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['student_id'];
            $fullname = trim($_POST['fullname']);
            $email = trim($_POST['email']);
            $course = trim($_POST['course']);
            
            if ($fullname && $email) {
                // Check if email is already taken by another user
                $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $check->execute([$email, $id]);
                if ($check->fetch()) {
                    $error = "Email address is already in use by another user.";
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, course = ? WHERE id = ? AND role = 'student'");
                    if ($stmt->execute([$fullname, $email, $course, $id])) {
                        $msg = "Student information updated successfully!";
                    } else {
                        $error = "Failed to update student.";
                    }
                }
            } else {
                $error = "Fullname and Email are required fields.";
            }
        }
    }
}
?>

<div class="card">
    <div class="card-title"><i class="fa-solid fa-user-graduate"></i> Manage Student Members</div>
    <p style="color: #6c757d; margin-bottom: 20px;">Review, update, or remove student accounts registered in the system.</p>
    
    <?php if($msg): ?><div class="msg" style="background:#d1e7dd; color:#0f5132; padding:15px; border-radius:5px; margin-bottom:15px;"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if($error): ?><div class="msg" style="background:#f8d7da; color:#842029; padding:15px; border-radius:5px; margin-bottom:15px;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Course</th>
                    <th>Joined Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM users WHERE role = 'student' ORDER BY fullname ASC");
                $students = $stmt->fetchAll();
                foreach($students as $row):
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['fullname']) ?></strong></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><span style="color: #6c757d;"><i class="fa-solid fa-graduation-cap" style="margin-right: 5px;"></i><?= htmlspecialchars($row['course'] ?? 'N/A') ?></span></td>
                    <td style="font-size: 13px; color: #4a5568;"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                    <td style="text-align: right; white-space: nowrap;">
                        <button onclick="editMember(<?= $row['id'] ?>, '<?= addslashes(htmlspecialchars($row['fullname'])) ?>', '<?= addslashes(htmlspecialchars($row['email'])) ?>', '<?= addslashes(htmlspecialchars($row['course'] ?? '')) ?>')" class="btn" style="background:#ffc107; color:#212529; font-size: 12px; padding: 6px 12px;">
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        
                        <form method="POST" style="display:inline-block; margin:0;" onsubmit="return confirm('Permanently delete this student account? This will also remove their organization applications.');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($students)): ?>
                <tr><td colspan="5" style="text-align:center; padding: 30px; color: #6c757d;">No students registered yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<form id="editMemberForm" method="POST" style="display:none;">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="student_id" id="edit_student_id">
    <input type="hidden" name="fullname" id="edit_fullname">
    <input type="hidden" name="email" id="edit_email">
    <input type="hidden" name="course" id="edit_course">
</form>

<script>
function editMember(id, name, email, course) {
    let newName = prompt("Edit Full Name:", name);
    if (newName !== null && newName.trim() !== "") {
        let newEmail = prompt("Edit Email Address:", email);
        if (newEmail !== null && newEmail.trim() !== "") {
            let newCourse = prompt("Edit Course (e.g., BSIT, BSED):", course);
            if (newCourse !== null) {
                document.getElementById('edit_student_id').value = id;
                document.getElementById('edit_fullname').value = newName;
                document.getElementById('edit_email').value = newEmail;
                document.getElementById('edit_course').value = newCourse;
                document.getElementById('editMemberForm').submit();
            }
        }
    }
}
</script>

<?php require_once 'admin_footer.php'; ?>