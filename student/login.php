<?php
session_name('student_portal');
session_start();
require_once '../db.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'student') header("Location: dashboard.php");
    else header("Location: ../subadmin/dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'student'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            // INSERT INTO login_logs
            $logStmt = $pdo->prepare("INSERT INTO login_logs (user_id, login_time, status) VALUES (?, NOW(), 'ONLINE')");
            $logStmt->execute([$user['id']]);

            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please enter both email and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | ADSSU</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0F5132;
            --secondary: #FFC107;
            --bg-color: #f0f4f8;
            --text-main: #2b3a4a;
        }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: var(--bg-color); color: var(--text-main); display: flex; min-height: 100vh; align-items: center; justify-content: center; }
        .container {
            background: #fff; padding: 40px 35px; border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            width: 100%; max-width: 400px; text-align: center;
        }
        .logo { width: 80px; margin-bottom: 10px; }
        h2 { margin: 0 0 25px; color: var(--primary); font-weight: 800; font-size: 24px; }
        .form-group { margin-bottom: 18px; text-align: left; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #555; }
        input {
            width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px;
            box-sizing: border-box; font-family: 'Outfit', sans-serif; transition: all 0.3s;
        }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.2); }
        .btn {
            width: 100%; padding: 12px; background: var(--primary); color: white;
            border: none; border-radius: 8px; font-size: 16px; font-weight: 600;
            cursor: pointer; transition: 0.3s; margin-top: 10px;
        }
        .btn:hover { background: #0a3d24; }
        .error { background: #f8d7da; color: #842029; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-weight: 600; }
        .links { margin-top: 20px; font-size: 14px; }
        .links a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <img src="../adssu.png" alt="ADSSU Logo" class="logo">
    <h2>Student Login</h2>
    
    <?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required placeholder="Enter email">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter password">
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    
    <div class="links">
        No account yet? <a href="register.php">Register here</a><br><br>
        <a href="../index.php"> </a>
    </div>
</div>

</body>
</html>
