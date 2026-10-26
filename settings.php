<?php
$adminPage = 'Settings';
require_once dirname(__DIR__) . '/includes/init.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf'] ?? '')) {
    $action = $_POST['action'] ?? '';
    if ($action === 'settings') {
        $keys = $_POST['keys'] ?? [];
        $vals = $_POST['vals'] ?? [];
        $up = $pdo->prepare("INSERT INTO site_settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");
        foreach ($keys as $i => $k) $up->execute([$k, $vals[$i] ?? '']);
        flash('success', 'Settings saved.');
    } elseif ($action === 'social_save') {
        $id = (int)($_POST['id'] ?? 0);
        $plat = trim($_POST['platform'] ?? '');
        $url  = trim($_POST['url'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-solid fa-link');
        $ord  = (int)($_POST['sort_order'] ?? 0);
        $act  = isset($_POST['active']) ? 1 : 0;
        if ($id) $pdo->prepare("UPDATE social_links SET platform=?,url=?,icon=?,sort_order=?,active=? WHERE id=?")->execute([$plat,$url,$icon,$ord,$act,$id]);
        else     $pdo->prepare("INSERT INTO social_links (platform,url,icon,sort_order,active) VALUES (?,?,?,?,?)")->execute([$plat,$url,$icon,$ord,$act]);
        flash('success','Social link saved.');
    } elseif ($action === 'social_delete') {
        $pdo->prepare("DELETE FROM social_links WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success','Deleted.');
    } elseif ($action === 'password') {
        $old = $_POST['old_pass'] ?? '';
        $new = $_POST['new_pass'] ?? '';
        $stmt = $pdo->prepare("SELECT password FROM admins WHERE id=?");
        $stmt->execute([$_SESSION['admin_id']]);
        $row = $stmt->fetch();
        if ($row && password_verify($old, $row['password']) && strlen($new) >= 6) {
            $pdo->prepare("UPDATE admins SET password=? WHERE id=?")->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION['admin_id']]);
            flash('success','Password updated.');
        } else {
            flash('error','Password update failed. Check old password (min new 6 chars).');
        }
    }
    header('Location: ' . url('admin/settings.php')); exit;
}
$all = $pdo->query("SELECT * FROM site_settings ORDER BY setting_key")->fetchAll();
$socials = $pdo->query("SELECT * FROM social_links ORDER BY sort_order")->fetchAll();
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<main class="admin-main">
  <div class="admin-top"><h1>Settings</h1></div>
  <?php if ($f = flash('success')): ?><div style="background:rgba(34,197,94,.1); color:#22c55e; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;"><?= e($f) ?></div><?php endif; ?>
  <?php if ($f = flash('error')): ?><div style="background:rgba(239,68,68,.1); color:#ef4444; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;"><?= e($f) ?></div><?php endif; ?>

  <div class="admin-card" style="margin-bottom:20px;">
    <h3 style="margin:0 0 16px;">Website Content</h3>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="settings">
      <?php foreach ($all as $s): ?>
        <div class="form-row">
          <label><?= e($s['setting_key']) ?></label>
          <?php if (strlen((string)$s['setting_value']) > 80 || preg_match('/(desc|bio|philosophy|intro|note)/', $s['setting_key'])): ?>
            <textarea name="vals[]" rows="3"><?= e($s['setting_value']) ?></textarea>
          <?php else: ?>
            <input type="text" name="vals[]" value="<?= e($s['setting_value']) ?>">
          <?php endif; ?>
          <input type="hidden" name="keys[]" value="<?= e($s['setting_key']) ?>">
        </div>
      <?php endforeach; ?>
      <button class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Settings</button>
    </form>
  </div>

  <div class="admin-card" style="margin-bottom:20px;">
    <h3 style="margin:0 0 16px;">Social &amp; Contact Links</h3>
    <table style="margin-bottom:20px;">
      <thead><tr><th>Platform</th><th>URL</th><th>Icon</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($socials as $s): ?>
          <tr>
            <td><?= e($s['platform']) ?></td>
            <td style="word-break:break-all; max-width:300px;"><a href="<?= e($s['url']) ?>" target="_blank" style="color:var(--accent);"><?= e($s['url']) ?></a></td>
            <td><i class="<?= e($s['icon']) ?>"></i></td>
            <td>
              <form method="post" style="display:inline;" onsubmit="return confirmDelete('Delete this link?');">
                <?= csrf_field() ?><input type="hidden" name="action" value="social_delete"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <button class="btn btn-sm btn-outline" style="color:#ef4444; border-color:rgba(239,68,68,.4);"><i class="fa-solid fa-trash"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="social_save">
      <div class="grid-2">
        <div class="form-row"><label>Platform</label><input type="text" name="platform" placeholder="whatsapp / email / facebook" required></div>
        <div class="form-row"><label>URL</label><input type="text" name="url" placeholder="https://..." required></div>
      </div>
      <div class="grid-2">
        <div class="form-row"><label>Icon class</label><input type="text" name="icon" value="fa-brands fa-whatsapp"></div>
        <div class="form-row"><label>Sort Order</label><input type="number" name="sort_order" value="0"></div>
      </div>
      <div class="form-row"><label><input type="checkbox" name="active" checked> Active</label></div>
      <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Link</button>
    </form>
  </div>

  <div class="admin-card">
    <h3 style="margin:0 0 16px;">Change Password</h3>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="password">
      <div class="grid-2">
        <div class="form-row"><label>Current Password</label><input type="password" name="old_pass" required></div>
        <div class="form-row"><label>New Password (min 6)</label><input type="password" name="new_pass" required minlength="6"></div>
      </div>
      <button class="btn btn-primary"><i class="fa-solid fa-key"></i> Change Password</button>
    </form>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>