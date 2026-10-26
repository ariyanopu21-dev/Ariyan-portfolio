<?php
// ⚠️ Run this file ONCE in browser, then DELETE it.
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'ariyanmirza1432';
$pass = 'XTmJ3sAtJKKQf#Av';
$dbName = 'ariyanmirzawedding';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");

    $sql = file_get_contents(__DIR__ . '/database/database.sql');
    $pdo->exec($sql);

    // Reset admins table and create your account
    $pdo->exec("DELETE FROM admins");
    $stmt = $pdo->prepare("INSERT INTO admins (username,email,password) VALUES (?,?,?)");
    $stmt->execute([
        '@Admin@Ariyan',
        'ariyanopu21@gmail.com',
        password_hash('ariyanmirza@@', PASSWORD_DEFAULT)
    ]);

    // Ensure social links match your real accounts
    $pdo->exec("DELETE FROM social_links");
    $ins = $pdo->prepare("INSERT INTO social_links (platform,url,icon,sort_order) VALUES (?,?,?,?)");
    $ins->execute(['whatsapp', 'https://wa.me/8801337902836',       'fa-brands fa-whatsapp',   1]);
    $ins->execute(['email',    'mailto:ariyanopu21@gmail.com',       'fa-solid fa-envelope',    2]);
    $ins->execute(['facebook', 'https://www.facebook.com/ariyanmirzash', 'fa-brands fa-facebook-f', 3]);
    $ins->execute(['instagram','https://instagram.com/ariyanmirzash','fa-brands fa-instagram',  4]);

    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Install Complete</title>
    <style>body{font-family:system-ui,sans-serif;background:#0c0c0d;color:#f2f2f2;display:grid;place-items:center;min-height:100vh;margin:0;padding:20px}
    .card{background:#17171a;padding:40px;border-radius:16px;border:1px solid rgba(255,255,255,.08);max-width:520px;width:100%}
    h1{color:#22c55e;margin:0 0 20px;font-size:1.6rem}
    .row{background:rgba(184,150,90,.1);padding:14px;border-radius:10px;margin-bottom:12px;font-size:15px}
    .row b{color:#c9a86a}
    .warn{color:#ef4444;font-weight:600;margin-top:22px;font-size:14px}
    code{background:#0c0c0d;padding:2px 8px;border-radius:4px;color:#c9a86a;font-size:14px}
    a.btn{display:inline-block;margin-top:16px;background:#b8965a;color:#fff;padding:12px 24px;border-radius:999px;text-decoration:none;font-weight:600}
    </style></head><body><div class='card'>
    <h1>✅ Installation Complete</h1>
    <div class='row'><b>Admin URL:</b> <code>/admin/login.php</code></div>
    <div class='row'><b>Username:</b> <code>@Admin@Ariyan</code></div>
    <div class='row'><b>Password:</b> <code>ariyanmirza@@</code></div>
    <div class='warn'>⚠️ IMPORTANT: Delete <code>install.php</code> now before going live!</div>
    <a href='/admin/login.php' class='btn'>Go to Admin Login →</a>
    </div></body></html>";

} catch (Exception $e) {
    echo "<h2 style='color:#ef4444;font-family:sans-serif;'>❌ Installation Error</h2>";
    echo "<pre style='background:#222;color:#eee;padding:20px;border-radius:10px;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
}