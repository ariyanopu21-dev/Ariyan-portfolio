<?php
$adminPage = 'Albums';
require_once dirname(__DIR__) . '/includes/init.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (csrf_verify($_POST['csrf'] ?? '')) {
        $id = (int)$_POST['id'];
        $photos = $pdo->prepare("SELECT filename, thumbnail FROM photos WHERE album_id=?");
        $photos->execute([$id]);
        foreach ($photos->fetchAll() as $p) {
            @unlink(BASE_PATH . '/uploads/photos/' . $p['filename']);
            @unlink(BASE_PATH . '/uploads/thumbnails/' . $p['thumbnail']);
        }
        $pdo->prepare("DELETE FROM albums WHERE id=?")->execute([$id]);
        flash('success', 'Album deleted.');
    }
    header('Location: ' . url('admin/albums.php')); exit;
}

$albums = $pdo->query("SELECT a.*, (SELECT COUNT(*) FROM photos WHERE album_id=a.id) AS photo_count FROM albums a ORDER BY created_at DESC")->fetchAll();
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<main class="admin-main">
  <div class="admin-top">
    <h1>Albums</h1>
    <a href="<?= url('admin/album-create.php') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Album</a>
  </div>
  <?php if ($f = flash('success')): ?><div style="background:rgba(34,197,94,.1); color:#22c55e; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;"><?= e($f) ?></div><?php endif; ?>
  <?php if (empty($albums)): ?>
    <div class="admin-card" style="text-align:center; padding:60px;"><i class="fa-solid fa-images" style="font-size:3rem; color:var(--accent); opacity:.5;"></i><p style="color:var(--text-muted);">No albums yet.</p><a href="<?= url('admin/album-create.php') ?>" class="btn btn-primary">Create your first album</a></div>
  <?php else: ?>
    <div class="admin-card" style="padding:0; overflow:auto;">
      <table>
        <thead><tr><th>Thumb</th><th>Title</th><th>Category</th><th>Photos</th><th>Featured</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <?php foreach ($albums as $a): ?>
          <tr>
            <td><img src="<?= e($a['thumbnail'] ? upload_url('thumbnails/'.$a['thumbnail']) : asset('images/placeholder.jpg')) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;"></td>
            <td><b><?= e($a['title']) ?></b><br><small style="color:var(--text-muted);"><?= e($a['slug']) ?></small></td>
            <td><?= e($a['category']) ?></td>
            <td><?= (int)$a['photo_count'] ?></td>
            <td><?= $a['featured'] ? '<span class="badge badge-gold">Featured</span>' : '—' ?></td>
            <td><?= $a['published'] ? '<span class="badge badge-green">Published</span>' : '<span class="badge badge-gray">Draft</span>' ?></td>
            <td style="white-space:nowrap;">
              <a href="<?= url('admin/photos.php?album_id='.$a['id']) ?>" class="btn btn-sm btn-outline" title="Photos"><i class="fa-solid fa-image"></i></a>
              <a href="<?= url('admin/album-edit.php?id='.$a['id']) ?>" class="btn btn-sm btn-outline" title="Edit"><i class="fa-solid fa-pen"></i></a>
              <form method="post" style="display:inline;" onsubmit="return confirmDelete('Delete this album and all its photos?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline" style="color:#ef4444; border-color:rgba(239,68,68,.4);"><i class="fa-solid fa-trash"></i></button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>