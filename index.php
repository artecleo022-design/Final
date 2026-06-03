<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADSSU Student Organizations System</title>
    <meta name="description" content="Welcome to the ADSSU Student Organizations System. Select your portal to continue.">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0F5132;
            --primary-light: #1e8e58;
            --secondary: #FFC107;
            --bg-color: #F8F9FA;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
        }
        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(-45deg, #0F5132, #1e8e58, #0a3622, #27a76c);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Animated abstract shapes in background */
        .bg-shapes {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }
        .shape {
            position: absolute;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 20s infinite alternate;
        }
        .shape-1 {
            width: 400px; height: 400px;
            background: #FFC107;
            top: -100px; left: -100px;
            border-radius: 50%;
        }
        .shape-2 {
            width: 500px; height: 500px;
            background: #27a76c;
            bottom: -150px; right: -100px;
            border-radius: 50%;
            animation-delay: -5s;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(80px, 80px) scale(1.2); }
        }

        /* Glassmorphism Portal Container */
        .portal-container {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 45px rgba(0,0,0,0.25);
            text-align: center;
            max-width: 550px;
            width: 90%;
            position: relative;
            z-index: 1;
            transform: translateY(0);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }
        .portal-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }
        
        .logo { 
            width: 130px; 
            margin-bottom: 25px; 
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.15));
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .logo:hover {
            transform: scale(1.08) rotate(-2deg);
        }

        h1 { 
            color: var(--primary); 
            margin-bottom: 12px; 
            font-weight: 800; 
            font-size: 2.2rem;
            letter-spacing: -0.5px;
        }
        p { 
            color: #555; 
            margin-bottom: 40px; 
            font-size: 1.1rem;
            line-height: 1.5;
            font-weight: 400;
        }

        .options {
            display: flex;
            gap: 25px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .portal-card {
            flex: 1;
            min-width: 200px;
            padding: 30px 20px;
            background: white;
            border: 2px solid transparent;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 10px 20px rgba(0,0,0,0.04);
        }
        
        .portal-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255,193,7,0.15) 0%, rgba(255,255,255,0) 100%);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .portal-card:hover {
            border-color: var(--secondary);
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 30px rgba(15,81,50,0.15);
        }
        .portal-card:hover::before {
            opacity: 1;
        }

        .student { background: linear-gradient(to bottom, #ffffff, #f0fdf4); }
        .admin { background: linear-gradient(to bottom, #ffffff, #fffcf0); }

        .icon-wrapper {
            width: 75px;
            height: 75px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 8px 15px rgba(0,0,0,0.06);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .portal-card:hover .icon-wrapper {
            transform: scale(1.15) rotate(5deg);
        }
        .icon { font-size: 36px; }

        .portal-card h3 { 
            margin: 18px 0 8px; 
            color: #222; 
            font-weight: 700;
            font-size: 1.35rem;
        }
        .card-desc {
            font-size: 13px; 
            color: #666;
            display: block;
            margin-top: 5px;
            font-weight: 400;
            line-height: 1.4;
        }
        
        .footer {
            margin-top: 35px; 
            font-size: 13px; 
            color: #888;
            font-weight: 400;
        }
    </style>
</head>
<body>

<div class="bg-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
</div>

<div class="portal-container">
    <img src="adssu.png" alt="ADSSU Logo" class="logo">
    <h1>Welcome to ADSSU</h1>
    <p>Please select your destination portal to continue.</p>
    
    <div class="options">
        <a href="student/login.php" class="portal-card student" id="student-portal">
            <div class="icon-wrapper">
                <div class="icon">🎓</div>
            </div>
            <h3>Student Portal</h3>
            <span class="card-desc">Apply for Orgs & View Announcements</span>
        </a>

        <a href="subadmin/login.php" class="portal-card admin" id="admin-portal">
            <div class="icon-wrapper">
                <div class="icon">🛡️</div>
            </div>
            <h3>Sub-Admin Portal</h3>
            <span class="card-desc">Manage Applications & Reports</span>
        </a>
    </div>
    
    <div class="footer">
        &copy; <?php echo date('Y'); ?> Agusan Del Sur State College of Agriculture and Technology
    </div>
</div>

</body>
</html>
