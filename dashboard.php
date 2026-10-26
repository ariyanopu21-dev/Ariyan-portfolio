<?php
$adminPage = 'Dashboard';
require_once dirname(__DIR__) . '/includes/init.php';
require_admin();
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';

$totalAlbums = $pdo->query("SELECT COUNT(*) FROM albums")->fetchColumn();
$totalPhotos = $pdo->query("SELECT COUNT(*) FROM photos")->fetchColumn();
$featured    = $pdo->query("SELECT COUNT(*) FROM albums WHERE featured=1")->fetchColumn();
$published   = $pdo->query("SELECT COUNT(*) FROM albums WHERE published=1")->fetchColumn();
?>
<main class="admin-main">
  <div class="admin-top">
    <div>
      <h1>Dashboard</h1>
      <p style="color:var(--text-muted); margin:4px 0 0;">Welcome back, <b><?= e($_SESSION['admin_name']) ?></b></p>
    </div>
  </div>
  <div class="grid-4" style="margin-bottom:30px;">
    <div class="stat-card"><div class="sc-val"><?= (int)$totalAlbums ?></div><div class="sc-label">Total Albums</div></div>
    <div class="stat-card"><div class="sc-val"><?= (int)$totalPhotos ?></div><div class="sc-label">Total Photos</div></div>
    <div class="stat-card"><div class="sc-val"><?= (int)$featured ?></div><div class="sc-label">Featured Albums</div></div>
    <div class="stat-card"><div class="sc-val"><?= (int)$published ?></div><div class="sc-label">Published Albums</div></div>
  </div>
  <div class="admin-card">
    <h3 style="margin:0 0 16px;">Quick Actions</h3>
    <div style="display:flex; gap:12px; flex-wrap:wrap;">
      <a href="<?= url('admin/album-create.php') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Album</a>
      <a href="<?= url('admin/albums.php') ?>" class="btn btn-outline"><i class="fa-solid fa-images"></i> Manage Albums</a>
      <a href="<?= url('admin/services.php') ?>" class="btn btn-outline"><i class="fa-solid fa-list-check"></i> Services</a>
      <a href="<?= url('admin/settings.php') ?>" class="btn btn-outline"><i class="fa-solid fa-gear"></i> Settings</a>
    </div>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>