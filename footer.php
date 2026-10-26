<?php $year = date('Y'); ?>
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="brand" style="margin-bottom:18px;"><span class="brand-mark">AM</span><span>Ariyan Mirza<small>Wedding Editor</small></span></div>
        <p style="color:var(--text-muted); max-width:380px; font-size:14.5px;"><?= e(setting('footer_note_'.current_lang(), setting('footer_note_en'))) ?></p>
      </div>
      <div>
        <h5><?= t('quick_links') ?></h5>
        <ul class="footer-links">
          <li><a href="<?= url('') ?>"><?= t('nav_home') ?></a></li>
          <li><a href="<?= url('about.php') ?>"><?= t('nav_about') ?></a></li>
          <li><a href="<?= url('services.php') ?>"><?= t('nav_services') ?></a></li>
          <li><a href="<?= url('work.php') ?>"><?= t('nav_work') ?></a></li>
          <li><a href="<?= url('contact.php') ?>"><?= t('nav_contact') ?></a></li>
        </ul>
      </div>
      <div>
        <h5><?= t('follow') ?></h5>
        <div class="footer-social">
          <?php foreach ($socialLinks as $s): ?>
            <a href="<?= e($s['url']) ?>" target="_blank" rel="noopener" aria-label="<?= e($s['platform']) ?>"><i class="<?= e($s['icon']) ?>"></i></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= $year ?> Ariyan Mirza. <?= t('rights') ?></span>
      <span style="font-size:12px; letter-spacing:.15em; text-transform:uppercase;">Crafted with care</span>
    </div>
  </div>
</footer>
<button class="scroll-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top"><i class="fa-solid fa-arrow-up"></i></button>
<script src="<?= asset('js/main.js') ?>?v=1"></script>
</body>
</html>