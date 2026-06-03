<?php
// Support DATABASE_URL (Railway provides this for MySQL addon)
$dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: '';

if ($dbUrl) {
    $parts = parse_url($dbUrl);
    $host = $parts['host'] ?? '127.0.0.1';
    $username = $parts['user'] ?? 'root';
    $password = $parts['pass'] ?? '';
    $dbname = ltrim($parts['path'] ?? 'adssu_student_orgs', '/');
    $port = $parts['port'] ?? '3306';
} else {
    $host = getenv('MYSQL_HOST') ?: '127.0.0.1';
    $username = getenv('MYSQL_USER') ?: 'root';
    $password = getenv('MYSQL_PASSWORD') ?: '';
    $dbname = getenv('MYSQL_DATABASE') ?: 'adssu_student_orgs';
    $port = getenv('MYSQL_PORT') ?: '3306';
}

try {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => false,
    ];
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password, $options);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
