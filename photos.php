<?php
$adminPage = 'Photos';
require_once dirname(__DIR__) . '/includes/init.php';
require_admin();

$albumId = (int)($_GET['album_id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM albums WHERE id=?");
$stmt->execute([$albumId]);
$album = $stmt->fetch();
if (!$album) { header('Location: ' . url('admin/albums.php')); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf'] ?? '')) {
    $action = $_POST['action'] ?? '';
    if ($action === 'upload' && !empty($_FILES['photos']['tmp_name'][0])) {
        $files = $_FILES['photos'];
        $maxOrder = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM photos WHERE album_id=$albumId")->fetchColumn();
        foreach ($files['tmp_name'] as $i => $tmp) {
            if (!is_uploaded_file($tmp) || !is_safe_image($tmp, $files['name'][$i])) continue;
            $fname = secure_upload_name($files['name'][$i]);
            $dest = BASE_PATH . '/uploads/photos/' . $fname;
            if (move_uploaded_file($tmp, $dest)) {
                make_thumbnail($dest, BASE_PATH . '/uploads/thumbnails/' . $fname, 700);
                $ins = $pdo->prepare("INSERT INTO photos (album_id,filename,original_filename,thumbnail,sort_order) VALUES (?,?,?,?,?)");
                $ins->execute([$albumId, $fname, $files['name'][$i], $fname, ++$maxOrder]);
            }
        }
        flash('success', 'Photos uploaded.');
    } elseif ($action === 'delete' && !empty($_POST['photo_id'])) {
        $pid = (int)$_POST['photo_id'];
        $p = $pdo->prepare("SELECT filename,thumbnail FROM photos WHERE id=? AND album_id=?");
        $p->execute([$pid, $albumId]);
        $row = $p->fetch();
        if ($row) {
            @unlink(BASE_PATH . '/uploads/photos/' . $row['filename']);
            @unlink(BASE_PATH . '/uploads/thumbnails/' . $row['thumbnail']);
            $pdo->prepare("DELETE FROM photos WHERE id=?")->execute([$pid]);
        }
        flash('success', 'Photo deleted.');
    } elseif ($action === 'set_cover' && !empty($_POST['photo_id'])) {
        $pid = (int)$_POST['photo_id'];
        $p = $pdo->prepare("SELECT filename FROM photos WHERE id=? AND album_id=?");
        $p->execute([$pid, $albumId]);
        $row = $p->fetch();
        if ($row) {
            $pdo->prepare("UPDATE albums SET thumbnail=? WHERE id=?")->execute([$row['filename'], $albumId]);
            flash('success', 'Cover updated.');
        }
    } elseif ($action === 'reorder' && !empty($_POST['order'])) {
        $order = json_decode($_POST['order'], true);
        if (is_array($order)) {
            $up = $pdo->prepare("UPDATE photos SET sort_order=? WHERE id=? AND album_id=?");
            foreach ($order as $i => $pid) $up->execute([$i, (int)$pid, $albumId]);
        }
        echo json_encode(['ok' => true]); exit;
    }
    header('Location: ' . url('admin/photos.php?album_id=' . $albumId)); exit;
}

$photos = $pdo->prepare("SELECT * FROM photos WHERE album_id=? ORDER BY sort_order ASC, id ASC");
$photos->execute([$albumId]);
$photos = $photos->fetchAll();

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<main class="admin-main">
  <div class="admin-top">
    <div>
      <h1>Photos — <?= e($album['title']) ?></h1>
      <p style="color:var(--text-muted); font-size:13px; margin:4px 0 0;"><?= count($photos) ?> photos • drag to reorder</p>
    </div>
    <a href="<?= url('admin/albums.php') ?>" class="btn btn-outline btn-sm"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
  <?php if ($f = flash('success')): ?><div style="background:rgba(34,197,94,.1); color:#22c55e; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;"><?= e($f) ?></div><?php endif; ?>
  <div class="admin-card" style="margin-bottom:20px;">
    <h3 style="margin:0 0 14px;">Upload Photos</h3>
    <form method="post" enctype="multipart/form-data" style="display:flex; gap:14px; align-items:flex-end; flex-wrap:wrap;">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="upload">
      <input type="file" name="photos[]" multiple accept="image/*" required>
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload</button>
    </form>
  </div>
  <?php if (empty($photos)): ?>
    <div class="admin-card" style="text-align:center; padding:50px; color:var(--text-muted);">No photos yet.</div>
  <?php else: ?>
    <div class="admin-card">
      <div class="photo-grid" id="photoGrid">
        <?php foreach ($photos as $p): ?>
          <div class="photo-tile" data-id="<?= (int)$p['id'] ?>">
            <img src="<?= upload_url('thumbnails/'.$p['thumbnail']) ?>" loading="lazy" alt="">
            <div class="pt-actions">
              <form method="post" style="display:inline;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="set_cover">
                <input type="hidden" name="photo_id" value="<?= (int)$p['id'] ?>">
                <button type="submit" title="Set as cover"><i class="fa-solid fa-star"></i></button>
              </form>
              <form method="post" style="display:inline;" onsubmit="return confirmDelete('Delete this photo?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="photo_id" value="<?= (int)$p['id'] ?>">
                <button type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
(function(){
  const grid = document.getElementById('photoGrid');
  if (!grid) return;
  Sortable.create(grid, {
    animation: 180,
    onEnd: function() {
      const order = Array.from(grid.children).map(el => el.dataset.id);
      const fd = new FormData();
      fd.append('csrf', '<?= csrf_token() ?>');
      fd.append('action', 'reorder');
      fd.append('order', JSON.stringify(order));
      fetch(location.href, { method:'POST', body: fd });
    }
  });
})();
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>