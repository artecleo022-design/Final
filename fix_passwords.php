<?php
require_once 'db.php';

// Fix Admin Password to admin123
$adminHash = password_hash('admin123', PASSWORD_DEFAULT);
$pdo->exec("UPDATE users SET password = '$adminHash' WHERE email = 'admin12'");

// Fix Student Passwords to student123
$studentHash = password_hash('student123', PASSWORD_DEFAULT);
$pdo->exec("UPDATE users SET password = '$studentHash' WHERE role = 'student'");

echo "Passwords updated successfully!";
?>
