<?php
require_once __DIR__ . '/includes/init.php';
$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM albums WHERE slug=? AND published=1 LIMIT 1");
$stmt->execute([$slug]);
$album = $stmt->fetch();
if (!$album) { http_response_code(404); include __DIR__ . '/404.php'; exit; }

$stmt = $pdo->prepare("SELECT * FROM photos WHERE album_id=? ORDER BY sort_order ASC, id ASC");
$stmt->execute([$album['id']]);
$photos = $stmt->fetchAll();

$pageTitle = e($album['title']) . ' — ' . setting('site_title');
$pageDesc  = $album['description'] ?: setting('site_description');

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>
<section class="page-hero" style="padding-bottom:30px;">
  <div class="container">
    <div class="eyebrow" style="justify-content:center;"><?= e($album['category']) ?><?= $album['event_date'] ? ' • ' . date('F Y', strtotime($album['event_date'])) : '' ?></div>
    <h1><?= e($album['title']) ?></h1>
    <?php if ($album['description']): ?><p><?= e($album['description']) ?></p><?php endif; ?>
    <div style="margin-top:20px;"><a href="<?= url('work.php') ?>" class="btn btn-outline btn-sm"><i class="fa-solid fa-arrow-left"></i> <?= t('back_to_work') ?></a></div>
  </div>
</section>
<section class="section" style="padding-top:10px;">
  <div class="container">
    <?php if (empty($photos)): ?>
      <div class="empty-state"><i class="fa-solid fa-image"></i><p><?= t('empty_albums') ?></p></div>
    <?php else: ?>
      <div class="masonry">
        <?php foreach ($photos as $p): ?>
          <div class="masonry-item" data-lightbox data-src="<?= upload_url('photos/' . $p['filename']) ?>">
            <img src="<?= upload_url('thumbnails/' . ($p['thumbnail'] ?: $p['filename'])) ?>" alt="<?= e($album['title']) ?>" loading="lazy">
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>