<?php
require_once 'student_header.php';

$msg = '';
$msg_type = 'success';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullname = $_POST['fullname'];
    $course = $_POST['course'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if ($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET fullname=?, course=?, email=?, password=? WHERE id=?");
        $stmt->execute([$fullname, $course, $email, $hash, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET fullname=?, course=?, email=? WHERE id=?");
        $stmt->execute([$fullname, $course, $email, $user_id]);
    }
    $_SESSION['fullname'] = $fullname;
    $msg = "Profile updated successfully!";
}

// Fetch user data
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch();
?>

<?php if($msg): ?>
    <div class="msg <?= $msg_type === 'error' ? 'msg-error' : '' ?>">
        <i class="fa-solid <?= $msg_type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check' ?>"></i>
        <div><?= htmlspecialchars($msg) ?></div>
    </div>
<?php endif; ?>

<div class="card" style="max-width:600px; margin: 0 auto; position: relative; overflow: hidden;">
    <i class="fa-solid fa-user-shield" style="position: absolute; right: -20px; bottom: -20px; font-size: 150px; opacity: 0.02; color: var(--text-main);"></i>
    
    <div class="card-title"><i class="fa-solid fa-user-pen"></i> Profile Settings</div>
    
    <form method="POST">
        <input type="hidden" name="update_profile" value="1">
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label><i class="fa-regular fa-id-badge"></i> Full Name</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
            </div>
            <div style="flex: 1; min-width: 250px;">
                <label><i class="fa-solid fa-graduation-cap"></i> Course / Program</label>
                <input type="text" name="course" value="<?= htmlspecialchars($user['course']) ?>" required>
            </div>
        </div>

        <label><i class="fa-regular fa-envelope"></i> Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        
        <label><i class="fa-solid fa-lock"></i> New Password <span style="text-transform: none; font-weight: 400; color: #6c757d;">(leave blank to keep current)</span></label>
        <input type="password" name="password" placeholder="••••••••">
        
        <div style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
        </div>
    </form>
</div>

<?php require_once 'student_footer.php'; ?>
