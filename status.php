<?php
require_once __DIR__ . '/db.php';
echo "<h2>System Status</h2>";

$stmt = $pdo->prepare("SELECT id, fullname, email, password, role FROM users WHERE email = 'admin' AND role = 'sub_admin'");
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    echo "<p style='color:green;'>✅ Admin user found</p>";
    echo "Name: {$user['fullname']}<br>";
    echo "Email: {$user['email']}<br>";
    echo "Role: {$user['role']}<br>";
    $hash = $user['password'];
    echo "Hash: " . substr($hash, 0, 20) . "...<br>";
    echo "Verify admin123: " . (password_verify('admin123', $hash) ? '<strong style="color:green;">✅ PASS</strong>' : '<strong style="color:red;">❌ FAIL</strong>') . "<br>";
} else {
    echo "<p style='color:red;'>❌ Admin user NOT found!</p>";
}

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'");
echo "<p>Students: " . $stmt->fetchColumn() . "</p>";

$stmt = $pdo->query("SELECT COUNT(*) FROM organizations");
echo "<p>Organizations: " . $stmt->fetchColumn() . "</p>";
