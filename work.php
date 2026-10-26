<?php
require_once __DIR__ . '/includes/init.php';
$albums = $pdo->query("SELECT * FROM albums WHERE published=1 ORDER BY created_at DESC")->fetchAll();
foreach ($albums as &$a) {
    $a['photo_count'] = $pdo->query("SELECT COUNT(*) FROM photos WHERE album_id=" . (int)$a['id'])->fetchColumn();
}
unset($a);
$pageTitle = 'Work — ' . setting('site_title');
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="eyebrow" style="justify-content:center;"><?= t('work_eyebrow') ?></div>
    <h1><?= t('all_albums') ?></h1>
    <p><?= t('all_albums_sub') ?></p>
  </div>
</section>
<section class="section" style="padding-top:20px;">
  <div class="container">
    <?php if (empty($albums)): ?>
      <div class="empty-state"><i class="fa-solid fa-images"></i><p><?= t('empty_albums') ?></p></div>
    <?php else: ?>
      <div class="albums-grid">
        <?php foreach ($albums as $a): ?>
          <a class="album-card reveal" href="<?= url('album.php?slug=' . urlencode($a['slug'])) ?>">
            <div class="album-thumb">
              <img src="<?= e($a['thumbnail'] ? upload_url('thumbnails/' . $a['thumbnail']) : asset('images/placeholder.jpg')) ?>" alt="<?= e($a['title']) ?>" loading="lazy">
              <span class="view-btn"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
            </div>
            <div class="album-overlay">
              <div class="album-cat"><?= e($a['category']) ?></div>
              <h3><?= e($a['title']) ?></h3>
              <div class="album-meta"><?= (int)$a['photo_count'] ?> <?= t('photos_count') ?><?= $a['event_date'] ? ' • ' . date('M Y', strtotime($a['event_date'])) : '' ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>