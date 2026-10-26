<?php
$adminPage = 'Services';
require_once dirname(__DIR__) . '/includes/init.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf'] ?? '')) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $icon = trim($_POST['icon'] ?? 'fa-solid fa-camera');
        $titleEn = trim($_POST['title_en'] ?? '');
        $titleBn = trim($_POST['title_bn'] ?? '');
        $descEn  = trim($_POST['desc_en'] ?? '');
        $descBn  = trim($_POST['desc_bn'] ?? '');
        $order   = (int)($_POST['sort_order'] ?? 0);
        $active  = isset($_POST['active']) ? 1 : 0;
        if ($id) {
            $pdo->prepare("UPDATE services SET icon=?,title_en=?,title_bn=?,desc_en=?,desc_bn=?,sort_order=?,active=? WHERE id=?")->execute([$icon,$titleEn,$titleBn,$descEn,$descBn,$order,$active,$id]);
        } else {
            $pdo->prepare("INSERT INTO services (icon,title_en,title_bn,desc_en,desc_bn,sort_order,active) VALUES (?,?,?,?,?,?,?)")->execute([$icon,$titleEn,$titleBn,$descEn,$descBn,$order,$active]);
        }
        flash('success', 'Service saved.');
    } elseif ($action === 'delete') {
        $pdo->prepare("DELETE FROM services WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success', 'Service deleted.');
    }
    header('Location: ' . url('admin/services.php')); exit;
}
$services = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC")->fetchAll();
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<main class="admin-main">
  <div class="admin-top"><h1>Services</h1></div>
  <?php if ($f = flash('success')): ?><div style="background:rgba(34,197,94,.1); color:#22c55e; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;"><?= e($f) ?></div><?php endif; ?>
  <div class="admin-card" style="margin-bottom:20px;">
    <h3 style="margin:0 0 16px;">Add New Service</h3>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <div class="grid-2">
        <div class="form-row"><label>Icon (Font Awesome class)</label><input type="text" name="icon" value="fa-solid fa-camera" placeholder="fa-solid fa-camera"></div>
        <div class="form-row"><label>Sort Order</label><input type="number" name="sort_order" value="0"></div>
      </div>
      <div class="grid-2">
        <div class="form-row"><label>Title (EN)</label><input type="text" name="title_en" required></div>
        <div class="form-row"><label>Title (BN)</label><input type="text" name="title_bn"></div>
      </div>
      <div class="grid-2">
        <div class="form-row"><label>Description (EN)</label><textarea name="desc_en" rows="2"></textarea></div>
        <div class="form-row"><label>Description (BN)</label><textarea name="desc_bn" rows="2"></textarea></div>
      </div>
      <div class="form-row"><label><input type="checkbox" name="active" value="1" checked> Active</label></div>
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Service</button>
    </form>
  </div>
  <div class="admin-card" style="padding:0; overflow:auto;">
    <table>
      <thead><tr><th>Icon</th><th>Title (EN)</th><th>Title (BN)</th><th>Order</th><th>Active</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($services as $s): ?>
          <tr>
            <td><i class="<?= e($s['icon']) ?>" style="font-size:18px;color:var(--accent);"></i></td>
            <td><?= e($s['title_en']) ?></td>
            <td><?= e($s['title_bn']) ?></td>
            <td><?= (int)$s['sort_order'] ?></td>
            <td><?= $s['active'] ? '<span class="badge badge-green">Yes</span>' : '<span class="badge badge-gray">No</span>' ?></td>
            <td style="white-space:nowrap;">
              <button class="btn btn-sm btn-outline" onclick='editService(<?= json_encode($s, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'><i class="fa-solid fa-pen"></i></button>
              <form method="post" style="display:inline;" onsubmit="return confirmDelete('Delete this service?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline" style="color:#ef4444; border-color:rgba(239,68,68,.4);"><i class="fa-solid fa-trash"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
<script>
function editService(s) {
  const modal = document.createElement('div');
  modal.style.cssText = 'position:fixed; inset:0; background:rgba(0,0,0,.6); display:grid; place-items:center; z-index:9999; padding:20px;';
  modal.innerHTML = `
    <div style="background:var(--card); border-radius:16px; padding:28px; max-width:640px; width:100%; max-height:90vh; overflow-y:auto;">
      <h3 style="margin:0 0 20px;">Edit Service</h3>
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="${s.id}">
        <div class="grid-2">
          <div class="form-row"><label>Icon</label><input type="text" name="icon" value="${s.icon}"></div>
          <div class="form-row"><label>Sort Order</label><input type="number" name="sort_order" value="${s.sort_order}"></div>
        </div>
        <div class="grid-2">
          <div class="form-row"><label>Title EN</label><input type="text" name="title_en" value="${s.title_en}"></div>
          <div class="form-row"><label>Title BN</label><input type="text" name="title_bn" value="${s.title_bn || ''}"></div>
        </div>
        <div class="form-row"><label>Desc EN</label><textarea name="desc_en" rows="2">${s.desc_en || ''}</textarea></div>
        <div class="form-row"><label>Desc BN</label><textarea name="desc_bn" rows="2">${s.desc_bn || ''}</textarea></div>
        <div class="form-row"><label><input type="checkbox" name="active" ${s.active==1?'checked':''}> Active</label></div>
        <div style="display:flex; gap:10px;"><button type="submit" class="btn btn-primary">Save</button><button type="button" class="btn btn-outline" onclick="this.closest('div[style*=fixed]').remove()">Cancel</button></div>
      </form>
    </div>`;
  document.body.appendChild(modal);
}
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>