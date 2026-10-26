<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'Contact — ' . setting('site_title');
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="eyebrow" style="justify-content:center;"><?= t('contact_eyebrow') ?></div>
    <h1><?= e(setting('contact_heading_'.current_lang())) ?></h1>
    <p><?= e(setting('contact_sub_'.current_lang())) ?></p>
  </div>
</section>
<section class="section" style="padding-top:20px;">
  <div class="container">
    <div class="contact-cards">
      <?php foreach ($socialLinks as $s): ?>
        <a class="contact-card reveal" href="<?= e($s['url']) ?>" target="_blank" rel="noopener">
          <div class="cc-icon"><i class="<?= e($s['icon']) ?>"></i></div>
          <h4><?= e(ucfirst($s['platform'])) ?></h4>
          <p><?= e(parse_url($s['url'], PHP_URL_HOST) ?: $s['url']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>