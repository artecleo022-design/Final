<?php
session_name('student_portal');
session_start();
require_once '../db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $course = trim($_POST['course'] ?? '');

    if ($fullname && $email && $password && $course) {
        $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, course, role) VALUES (?, ?, ?, ?, 'student')");
            if ($stmt->execute([$fullname, $email, $hash, $course])) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "An error occurred during registration.";
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | ADSSU</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0F5132;
            --secondary: #FFC107;
            --bg-color: #f0f4f8;
            --text-main: #2b3a4a;
        }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: var(--bg-color); color: var(--text-main); display: flex; min-height: 100vh; overflow-y: auto; }
        
        .split-layout { display: flex; width: 100%; min-height: 100vh; }
        
        /* Left Brand Side */
        .brand-side { flex: 1; background: linear-gradient(135deg, #198754 0%, #0a3d24 100%); color: white; display: none; flex-direction: column; justify-content: center; align-items: center; padding: 40px; position: relative; overflow: hidden; }
        @media (min-width: 900px) { .brand-side { display: flex; } }
        .brand-side::after { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml;utf8,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>') repeat; opacity: 0.5; pointer-events: none;}
        
        .brand-content { position: relative; z-index: 2; text-align: center; max-width: 500px; }
        .brand-logo { width: 150px; margin-bottom: 30px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2)); animation: float 6s ease-in-out infinite; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-15px); } 100% { transform: translateY(0px); } }
        .brand-title { font-size: 48px; font-weight: 800; margin: 0 0 15px 0; letter-spacing: -1px; text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .brand-subtitle { font-size: 18px; line-height: 1.6; opacity: 0.9; font-weight: 300; }
        
        /* Right Form Side */
        .form-side { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; background: white; position: relative; }
        .form-container { width: 100%; max-width: 480px; }
        
        .mobile-logo { display: block; width: 80px; margin: 0 auto 30px auto; }
        @media (min-width: 900px) { .mobile-logo { display: none; } }
        
        h2 { font-size: 32px; font-weight: 800; color: var(--text-main); margin: 0 0 10px 0; }
        p.subtitle { color: #6c757d; margin: 0 0 35px 0; font-size: 15px; }
        
        .form-row { display: flex; gap: 15px; flex-wrap: wrap; }
        .form-row .form-group { flex: 1; min-width: 200px; }
        
        .form-group { margin-bottom: 25px; position: relative; }
        .form-group i { position: absolute; top: 16px; left: 18px; color: #a0aec0; font-size: 18px; transition: 0.3s; }
        input { width: 100%; padding: 16px 20px 16px 50px; border: 2px solid #e2e8f0; border-radius: 12px; box-sizing: border-box; font-family: 'Outfit', sans-serif; font-size: 15px; background: #f8fafc; transition: all 0.3s ease; }
        input:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(15, 81, 50, 0.1); }
        input:focus + i { color: var(--primary); }
        
        .btn { width: 100%; padding: 16px; background: linear-gradient(135deg, #0F5132 0%, #1a6f43 100%); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 800; cursor: pointer; transition: 0.3s; box-shadow: 0 8px 20px rgba(15, 81, 50, 0.25); text-transform: uppercase; letter-spacing: 1px; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(15, 81, 50, 0.35); }
        .btn i { margin-right: 8px; }
        
        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-weight: 600; display: flex; align-items: center; }
        .alert i { margin-right: 10px; font-size: 20px; }
        .error { background: #fef2f2; color: #b91c1c; border-left: 4px solid #dc2626; }
        .success { background: #f0fdf4; color: #15803d; border-left: 4px solid #16a34a; }
        
        .links { margin-top: 35px; font-size: 15px; text-align: center; color: #6c757d; }
        .links a { color: var(--primary); text-decoration: none; font-weight: 800; transition: 0.3s; }
        .links a:hover { color: #0a3d24; text-decoration: underline; }
        
        .back-link { position: absolute; top: 30px; left: 30px; color: #a0aec0; text-decoration: none; font-weight: 600; transition: 0.3s; display: flex; align-items: center; }
        .back-link i { margin-right: 8px; }
        .back-link:hover { color: var(--primary); }
    </style>
</head>
<body>

<div class="split-layout">
    <div class="brand-side">
        <div class="brand-content">
            <img src="../adssu.png" alt="ADSSU Logo" class="brand-logo">
            <h1 class="brand-title">Join Our Community</h1>
            <p class="brand-subtitle">Become a part of the ADSSU student organizations. Explore, learn, and grow with us.</p>
        </div>
    </div>
    
    <div class="form-side">
        <a href="login.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Login</a>
        
        <div class="form-container">
            <img src="../adssu.png" alt="ADSSU Logo" class="mobile-logo">
            <h2>Create Account</h2>
            <p class="subtitle">Fill in your details to register as a student.</p>
            
            <?php if($error): ?>
                <div class="alert error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="alert success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="fullname" required placeholder="Full Name">
                        <i class="fa-regular fa-id-badge"></i>
                    </div>
                    <div class="form-group">
                        <input type="text" name="course" required placeholder="Course (e.g. BSIT)">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="email" name="email" required placeholder="Email Address">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="password" required placeholder="Password">
                    <i class="fa-solid fa-lock"></i>
                </div>
                
                <button type="submit" class="btn"><i class="fa-solid fa-user-plus"></i> Register Now</button>
            </form>
            
            <div class="links">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
