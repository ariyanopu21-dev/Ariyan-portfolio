<?php
$adminPage = 'Edit Album';
require_once dirname(__DIR__) . '/includes/init.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM albums WHERE id=?");
$stmt->execute([$id]);
$album = $stmt->fetch();
if (!$album) { header('Location: ' . url('admin/albums.php')); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? '')) $errors[] = 'Invalid session.';
    else {
        $title = trim($_POST['title'] ?? '');
        $desc  = trim($_POST['description'] ?? '');
        $cat   = trim($_POST['category'] ?? 'Wedding');
        $date  = $_POST['event_date'] ?? null;
        $featured  = isset($_POST['featured']) ? 1 : 0;
        $published = isset($_POST['published']) ? 1 : 0;
        $slug = trim($_POST['slug'] ?? '') ?: slugify($title);

        if (!$title) $errors[] = 'Title required.';

        $thumbName = $album['thumbnail'];
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            if (!is_safe_image($_FILES['thumbnail']['tmp_name'], $_FILES['thumbnail']['name'])) {
                $errors[] = 'Invalid thumbnail.';
            } else {
                $thumbName = secure_upload_name($_FILES['thumbnail']['name']);
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], BASE_PATH . '/uploads/thumbnails/' . $thumbName);
                @make_thumbnail(BASE_PATH . '/uploads/thumbnails/' . $thumbName, BASE_PATH . '/uploads/thumbnails/' . $thumbName, 900);
            }
        }

        if (empty($errors)) {
            $up = $pdo->prepare("UPDATE albums SET title=?,slug=?,description=?,category=?,event_date=?,thumbnail=?,featured=?,published=? WHERE id=?");
            $up->execute([$title,$slug,$desc,$cat,$date ?: null,$thumbName,$featured,$published,$id]);
            flash('success', 'Album updated.');
            header('Location: ' . url('admin/album-edit.php?id=' . $id)); exit;
        }
    }
}
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<main class="admin-main">
  <div class="admin-top"><h1>Edit Album</h1><div><a href="<?= url('admin/photos.php?album_id='.$id) ?>" class="btn btn-outline btn-sm"><i class="fa-solid fa-image"></i> Manage Photos</a> <a href="<?= url('admin/albums.php') ?>" class="btn btn-outline btn-sm"><i class="fa-solid fa-arrow-left"></i> Back</a></div></div>
  <?php if ($f = flash('success')): ?><div style="background:rgba(34,197,94,.1); color:#22c55e; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;"><?= e($f) ?></div><?php endif; ?>
  <?php if ($errors): ?><div style="background:rgba(239,68,68,.1); color:#ef4444; padding:12px; border-radius:8px; margin-bottom:20px;"><?php foreach($errors as $er) echo '<div>'.e($er).'</div>'; ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="admin-card">
    <?= csrf_field() ?>
    <div class="grid-2">
      <div class="form-row"><label>Title</label><input type="text" name="title" required value="<?= e($album['title']) ?>"></div>
      <div class="form-row"><label>Slug</label><input type="text" name="slug" value="<?= e($album['slug']) ?>"></div>
    </div>
    <div class="form-row"><label>Description</label><textarea name="description" rows="4"><?= e($album['description']) ?></textarea></div>
    <div class="grid-2">
      <div class="form-row"><label>Category</label><input type="text" name="category" value="<?= e($album['category']) ?>"></div>
      <div class="form-row"><label>Event Date</label><input type="date" name="event_date" value="<?= e($album['event_date']) ?>"></div>
    </div>
    <div class="grid-2">
      <div class="form-row"><label>Thumbnail</label><input type="file" name="thumbnail" accept="image/*">
        <?php if ($album['thumbnail']): ?><img src="<?= upload_url('thumbnails/'.$album['thumbnail']) ?>" style="margin-top:10px; max-width:180px; border-radius:8px;"><?php endif; ?>
      </div>
      <div class="form-row">
        <label><input type="checkbox" name="featured" value="1" <?= $album['featured']?'checked':'' ?>> Featured</label><br><br>
        <label><input type="checkbox" name="published" value="1" <?= $album['published']?'checked':'' ?>> Published</label>
      </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Update Album</button>
  </form>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>