<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'About — ' . setting('site_title');
$pageDesc  = setting('about_intro_en');
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="eyebrow" style="justify-content:center;"><?= t('about_eyebrow') ?></div>
    <h1><?= t('about_title') ?></h1>
    <p><?= e(setting('about_intro_'.current_lang())) ?></p>
  </div>
</section>

<section class="section" style="padding-top:20px;">
  <div class="container about-grid">
    <div class="about-images reveal">
      <div class="about-img-1"><img src="<?= asset('images/about-1.jpg') ?>" alt="Editing workspace" loading="lazy"></div>
      <div class="about-img-2"><img src="<?= asset('images/about-2.jpg') ?>" alt="Colour grading" loading="lazy"></div>
    </div>
    <div class="reveal">
      <h2 class="section-title"><?= t('about_title') ?></h2>
      <p style="color:var(--text-muted); font-size:16px; margin-bottom:18px;"><?= e(setting('about_intro_'.current_lang())) ?></p>
      <p style="color:var(--text-muted); font-size:15.5px; margin-bottom:28px;"><?= e(setting('about_bio_'.current_lang())) ?></p>
      <h3 style="font-size:1.25rem; color:var(--accent); margin-bottom:10px;"><?= t('about_philosophy') ?></h3>
      <p style="color:var(--text-muted); font-size:15.5px;"><?= e(setting('about_philosophy_'.current_lang())) ?></p>
      <div class="signature">Ariyan Mirza</div>
      <a href="<?= url('contact.php') ?>" class="btn btn-primary"><?= t('cta_contact') ?> <i class="fa-solid fa-arrow-right"></i></a>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>