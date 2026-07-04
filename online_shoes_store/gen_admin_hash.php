<?php
// run this short script via CLI or browser to generate hashed password for admin and print SQL line.
// Usage (PHP CLI): php gen_admin_hash.php
$pass = 'admin123';
echo password_hash($pass, PASSWORD_DEFAULT) . PHP_EOL;
?>