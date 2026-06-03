<?php
$file = 'database.sql';
$content = file_get_contents($file);

$adminHash = password_hash('admin123', PASSWORD_DEFAULT);
$studentHash = password_hash('student123', PASSWORD_DEFAULT);

$content = preg_replace('/INSERT IGNORE INTO `users` \(`id`, `fullname`, `email`, `password`, `role`\) VALUES \n\(1, \'System Admin\', \'admin\', \'.*?\', \'sub_admin\'\);/s', "INSERT IGNORE INTO `users` (`id`, `fullname`, `email`, `password`, `role`) VALUES \n(1, 'System Admin', 'admin', '$adminHash', 'sub_admin');", $content);

$content = preg_replace('/\'\$2y\$10\$D\/B1yHMyb0o\.q5\.B1o4YueS6x2\/oB7W8jVvQO4zC4\/e1M\.L8J7U2S\'/', "'$studentHash'", $content);

file_put_contents($file, $content);
echo "SQL updated";
?>
