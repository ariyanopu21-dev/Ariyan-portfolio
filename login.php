<?php
require_once dirname(__DIR__) . '/includes/init.php';
if (is_admin()) { header('Location: ' . url('admin/dashboard.php')); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? '')) { $error = 'Invalid session. Try again.'; }
    else {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=? OR email=? LIMIT 1");
        $stmt->execute([$u, $u]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($p, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            header('Location: ' . url('admin/dashboard.php'));
            exit;
        }
        $error = 'Invalid credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login — Ariyan Mirza</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body style="display:grid; place-items:center; min-height:100vh; padding:20px;">
<div style="width:100%; max-width:420px; background:var(--card); border:1px solid var(--border); border-radius:20px; padding:40px; box-shadow:var(--shadow);">
  <div style="text-align:center; margin-bottom:30px;">
    <span style="width:56px;height:56px;font-size:18px;display:inline-grid;place-items:center;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-2));color:#fff;font-weight:700;">AM</span>
    <h1 style="font-size:1.7rem; margin:16px 0 6px;">Admin Login</h1>
    <p style="color:var(--text-muted); font-size:13.5px; margin:0;">Ariyan Mirza Portfolio</p>
  </div>
  <?php if ($error): ?><div style="background:rgba(239,68,68,.1); color:#ef4444; padding:12px; border-radius:8px; font-size:13.5px; margin-bottom:16px;"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="form-row"><label>Username or Email</label><input type="text" name="username" required autofocus></div>
    <div class="form-row"><label>Password</label><input type="password" name="password" required></div>
    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Login</button>
  </form>
  <p style="text-align:center; margin-top:20px; font-size:12.5px; color:var(--text-muted);"><a href="<?= url('') ?>" style="color:var(--accent);">← Back to site</a></p>
</div>
<style>
  .form-row label { display:block; font-size:12.5px; font-weight:600; margin-bottom:7px; }
  .form-row input { width:100%; padding:12px 14px; border-radius:10px; border:1px solid var(--border); background:var(--bg); color:var(--text); font-family:inherit; font-size:14px; }
  .form-row input:focus { outline:none; border-color:var(--accent); }
</style>
</body>
</html>