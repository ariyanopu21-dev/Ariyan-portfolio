<?php if (!defined('BASE_URL')) { require_once __DIR__ . '/includes/init.php'; }
http_response_code(404);
$pageTitle = '404 — ' . setting('site_title', 'Ariyan Mirza');
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php'; ?>
<section class="section" style="padding:120px 0; text-align:center;">
  <div class="container">
    <h1 style="font-size:8rem; margin:0; color:var(--accent); line-height:1;">404</h1>
    <h2 style="margin:10px 0 20px;"><?= t('page_not_found') ?></h2>
    <a href="<?= url('') ?>" class="btn btn-primary"><?= t('go_home') ?></a>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>