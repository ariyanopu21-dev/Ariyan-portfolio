<?php
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = @iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'album-' . time();
}

function t($key) { global $translations; return $translations[$key] ?? $key; }
function setting($key, $default='') { global $settings; return isset($settings[$key]) && $settings[$key] !== '' ? $settings[$key] : $default; }
function current_lang() { return $GLOBALS['lang'] ?? 'en'; }

function url($path = '') { return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/'); }
function asset($p) { return url('assets/' . ltrim($p, '/')); }
function upload_url($p) { return url('uploads/' . ltrim($p, '/')); }

function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function csrf_verify($token) { return !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$token); }

function is_admin() { return !empty($_SESSION['admin_id']); }
function require_admin() { if (!is_admin()) { header('Location: ' . url('admin/login.php')); exit; } }

function flash($key, $msg = null) {
    if ($msg === null) { $v = $_SESSION['flash'][$key] ?? null; unset($_SESSION['flash'][$key]); return $v; }
    $_SESSION['flash'][$key] = $msg;
}

function secure_upload_name($orig) {
    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    return bin2hex(random_bytes(10)) . '.' . $ext;
}

function is_safe_image($tmp, $orig) {
    $allowed = ['jpg','jpeg','png','gif','webp'];
    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return false;
    $info = @getimagesize($tmp);
    if (!$info || !in_array($info['mime'], ['image/jpeg','image/png','image/gif','image/webp'])) return false;
    return true;
}

function make_thumbnail($src, $dst, $maxW = 800, $quality = 82) {
    $info = @getimagesize($src);
    if (!$info) return false;
    [$w, $h] = $info;
    switch ($info['mime']) {
        case 'image/jpeg': $img = imagecreatefromjpeg($src); break;
        case 'image/png':  $img = imagecreatefrompng($src); break;
        case 'image/gif':  $img = imagecreatefromgif($src); break;
        case 'image/webp': $img = function_exists('imagecreatefromwebp') ? imagecreatefromwebp($src) : null; break;
        default: return false;
    }
    if (!$img) return false;
    if ($w <= $maxW) { imagejpeg($img, $dst, $quality); imagedestroy($img); return true; }
    $ratio = $maxW / $w;
    $nw = $maxW;
    $nh = (int)($h * $ratio);
    $dstImg = imagecreatetruecolor($nw, $nh);
    imagecopyresampled($dstImg, $img, 0,0,0,0, $nw,$nh, $w,$h);
    imagejpeg($dstImg, $dst, $quality);
    imagedestroy($img);
    imagedestroy($dstImg);
    return true;
}