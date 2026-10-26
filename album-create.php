<?php
$adminPage = 'Create Album';
require_once dirname(__DIR__) . '/includes/init.php';
require_admin();

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

        if (!$title) $errors[] = 'Title is required.';
        $slug = slugify($title);
        $st = $pdo->prepare("SELECT COUNT(*) FROM albums WHERE slug=?");
        $st->execute([$slug]);
        if ($st->fetchColumn()) $slug .= '-' . time();

        $thumbName = null;
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            if (!is_safe_image($_FILES['thumbnail']['tmp_name'], $_FILES['thumbnail']['name'])) {
                $errors[] = 'Thumbnail must be a valid JPG/PNG/WEBP/GIF image.';
            } else {
                $thumbName = secure_upload_name($_FILES['thumbnail']['name']);
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], BASE_PATH . '/uploads/thumbnails/' . $thumbName);
                @make_thumbnail(BASE_PATH . '/uploads/thumbnails/' . $thumbName, BASE_PATH . '/uploads/thumbnails/' . $thumbName, 900);
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO albums (title,slug,description,category,event_date,thumbnail,featured,published) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$title, $slug, $desc, $cat, $date ?: null, $thumbName, $featured, $published]);
            $albumId = $pdo->lastInsertId();

            if (!empty($_FILES['photos']['tmp_name'][0])) {
                $files = $_FILES['photos'];
                $order = 0;
                foreach ($files['tmp_name'] as $i => $tmp) {
                    if (!is_uploaded_file($tmp)) continue;
                    if (!is_safe_image($tmp, $files['name'][$i])) continue;
                    $fname = secure_upload_name($files['name'][$i]);
                    $dest  = BASE_PATH . '/uploads/photos/' . $fname;
                    if (move_uploaded_file($tmp, $dest)) {
                        make_thumbnail($dest, BASE_PATH . '/uploads/thumbnails/' . $fname, 700);
                        $ins = $pdo->prepare("INSERT INTO photos (album_id,filename,original_filename,thumbnail,sort_order) VALUES (?,?,?,?,?)");
                        $ins->execute([$albumId, $fname, $files['name'][$i], $fname, $order++]);
                    }
                }
            }
            flash('success', 'Album created successfully.');
            header('Location: ' . url('admin/albums.php')); exit;
        }
    }
}
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<main class="admin-main">
  <div class="admin-top"><h1>Create Album</h1><a href="<?= url('admin/albums.php') ?>" class="btn btn-outline btn-sm"><i class="fa-solid fa-arrow-left"></i> Back</a></div>
  <?php if ($errors): ?><div style="background:rgba(239,68,68,.1); color:#ef4444; padding:12px; border-radius:8px; margin-bottom:20px;"><?php foreach($errors as $er) echo '<div>'.e($er).'</div>'; ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="admin-card">
    <?= csrf_field() ?>
    <div class="grid-2">
      <div class="form-row"><label>Album Title *</label><input type="text" name="title" required value="<?= e($_POST['title'] ?? '') ?>"></div>
      <div class="form-row"><label>Category</label><input type="text" name="category" value="<?= e($_POST['category'] ?? 'Wedding') ?>"></div>
    </div>
    <div class="form-row"><label>Description</label><textarea name="description" rows="4"><?= e($_POST['description'] ?? '') ?></textarea></div>
    <div class="grid-2">
      <div class="form-row"><label>Event Date</label><input type="date" name="event_date" value="<?= e($_POST['event_date'] ?? '') ?>"></div>
      <div class="form-row"><label>Thumbnail (cover)</label><input type="file" name="thumbnail" accept="image/*"></div>
    </div>
    <div class="form-row"><label>Photos (select multiple)</label><input type="file" name="photos[]" accept="image/*" multiple></div>
    <div class="grid-2">
      <div class="form-row"><label><input type="checkbox" name="featured" value="1"> Featured (show on homepage)</label></div>
      <div class="form-row"><label><input type="checkbox" name="published" value="1" checked> Published</label></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Create Album</button>
  </form>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>