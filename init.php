<?php
if (session_status() === PHP_SESSION_NONE) session_start();

define('BASE_PATH', dirname(__DIR__));
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if (preg_match('~/admin(/.*)?$~', $scriptDir)) $scriptDir = preg_replace('~/admin(/.*)?$~','',$scriptDir);
define('BASE_URL', $scheme . '://' . $host . $scriptDir);

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/includes/functions.php';

// Language
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'en');
if (!in_array($lang, ['en','bn'])) $lang = 'en';
$_SESSION['lang'] = $lang;
$GLOBALS['lang'] = $lang;
$translations = require BASE_PATH . "/languages/{$lang}.php";

// Settings
$settings = [];
try {
    $rows = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll();
    foreach ($rows as $r) $settings[$r['setting_key']] = $r['setting_value'];
} catch (Exception $e) {}

// Social
$socialLinks = [];
try {
    $socialLinks = $pdo->query("SELECT * FROM social_links WHERE active=1 ORDER BY sort_order")->fetchAll();
} catch (Exception $e) {}