<?php
$student_files = glob("student/*.php");
foreach ($student_files as $file) {
    $content = file_get_contents($file);
    // Session separation
    $content = str_replace("session_start();", "session_name('student_portal');\nsession_start();", $content);
    // Paths
    $content = str_replace("'db.php'", "'../db.php'", $content);
    $content = str_replace('"adssu.png"', '"../adssu.png"', $content);
    $content = str_replace('"student_login.php"', '"login.php"', $content);
    $content = str_replace('"student_dashboard.php"', '"dashboard.php"', $content);
    $content = str_replace('"student_register.php"', '"register.php"', $content);
    $content = str_replace("'student_dashboard.php'", "'dashboard.php'", $content);
    $content = str_replace('"index.php"', '"../index.php"', $content);
    file_put_contents($file, $content);
}

$subadmin_files = glob("subadmin/*.php");
foreach ($subadmin_files as $file) {
    $content = file_get_contents($file);
    // Session separation
    if (strpos($content, 'session_status() === PHP_SESSION_NONE') !== false) {
        $content = str_replace("session_start();", "session_name('subadmin_portal');\n    session_start();", $content);
    } else {
        $content = str_replace("session_start();", "session_name('subadmin_portal');\nsession_start();", $content);
    }
    // Paths
    $content = str_replace("'db.php'", "'../db.php'", $content);
    $content = str_replace('"adssu.png"', '"../adssu.png"', $content);
    $content = str_replace('"subadmin_login.php"', '"login.php"', $content);
    $content = str_replace('"subadmin_dashboard.php"', '"dashboard.php"', $content);
    $content = str_replace("'subadmin_dashboard.php'", "'dashboard.php'", $content);
    
    // admin_header specific
    $content = str_replace("'applications.php'", "'applications.php'", $content); // No change needed if in same folder
    
    // index fallback
    $content = str_replace('"index.php"', '"../index.php"', $content);
    // require_once 'admin_header.php' -> no change needed, they are in the same folder
    
    file_put_contents($file, $content);
}

// Update index.php
$index = file_get_contents("index.php");
// No session start change needed for index because index shouldn't start session, wait index DOES check session
// If index is root, it shouldn't auto redirect anymore if they are separate. Let's just remove session check in index.
$index_new = preg_replace('/<\?php\s*session_start\(\);\s*if \(isset\(\$_SESSION\[\'user_id\'\]\)\) \{.*?\s*\}\s*\?>\s*/s', '', $index);
$index_new = str_replace('"student_login.php"', '"student/login.php"', $index_new);
$index_new = str_replace('"subadmin_login.php"', '"subadmin/login.php"', $index_new);
file_put_contents("index.php", $index_new);

echo "Done";
