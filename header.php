<?php
if (!defined('BASE_URL')) require_once __DIR__ . '/init.php';
$pageTitle = $pageTitle ?? setting('site_title', 'Ariyan Mirza | Professional Wedding Photo Editor');
$pageDesc  = $pageDesc ?? setting('site_description', '');
?>
<!DOCTYPE html>
<html lang="<?= current_lang() ?>" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e($pageDesc) ?>">
<link rel="canonical" href="<?= e(BASE_URL . $_SERVER['REQUEST_URI']) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($pageTitle) ?>">
<meta property="og:description" content="<?= e($pageDesc) ?>">
<meta property="og:url" content="<?= e(BASE_URL . $_SERVER['REQUEST_URI']) ?>">
<meta property="og:image" content="<?= e(url('assets/images/profile.jpg')) ?>">
<link rel="icon" type="image/png" href="<?= asset('images/favicon.png') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=1">
<script>(function(){const t=localStorage.getItem('theme')||'light';document.documentElement.setAttribute('data-theme',t);})();</script>
</head>
<body>
<div class="page-loader"><div class="loader-ring"></div></div>