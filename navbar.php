<?php $current = basename($_SERVER['PHP_SELF']); ?>

<style>
/* ===== Self-contained navbar — will work even if style.css fails ===== */
.nb-promo { background: linear-gradient(90deg,#1a1a1a,#2c2620); color: #e5d9c0; font-size: 13px; padding: 8px 0; text-align: center; }
.nb-promo-inner { display: flex; justify-content: center; align-items: center; gap: 14px; flex-wrap: wrap; padding: 0 20px; }
.nb-promo .nb-dot { width: 6px; height: 6px; border-radius: 50%; background: #d9b97a; display: inline-block; animation: nbpulse 2s infinite; }
@keyframes nbpulse { 0%,100%{opacity:1} 50%{opacity:.35} }

.nb-nav { position: sticky; top: 0; z-index: 9999; background: var(--nav-bg, rgba(250,248,245,.95)); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); border-bottom: 1px solid var(--border, rgba(0,0,0,.08)); }
.nb-container { max-width: 1280px; margin: 0 auto; padding: 0 22px; }
.nb-inner { display: flex; align-items: center; justify-content: space-between; gap: 12px; min-height: 66px; padding: 10px 0; }
.nb-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--text, #171717); font-family: 'Playfair Display', serif; font-weight: 700; font-size: 20px; flex-shrink: 0; }
.nb-brand-mark { width: 38px; height: 38px; border-radius: 50%; display: grid; place-items: center; background: linear-gradient(135deg, var(--accent, #b8965a), var(--accent-2, #d9b97a)); color: #fff; font-size: 14px; font-weight: 700; flex-shrink: 0; }
.nb-brand-text { display: flex; flex-direction: column; line-height: 1.1; }
.nb-brand-text small { font-family: 'Inter', sans-serif; font-size: 9px; letter-spacing: .18em; text-transform: uppercase; color: var(--text-muted, #6b6b6b); font-weight: 500; }

.nb-links { display: flex; align-items: center; gap: 30px; list-style: none; margin: 0; padding: 0; }
.nb-links a { font-size: 14.5px; font-weight: 500; color: var(--text, #171717); text-decoration: none; padding: 6px 0; position: relative; transition: color .25s; }
.nb-links a.active, .nb-links a:hover { color: var(--accent, #b8965a); }

.nb-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.nb-btn { width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; cursor: pointer; background: var(--card, #fff); border: 1px solid var(--border, rgba(0,0,0,.08)); color: var(--text, #171717); font-size: 15px; padding: 0; transition: all .25s; }
.nb-btn:hover { border-color: var(--accent, #b8965a); color: var(--accent, #b8965a); }
.nb-lang { display: flex; align-items: center; gap: 4px; font-size: 13px; font-weight: 600; padding: 9px 14px; border-radius: 999px; background: var(--card, #fff); border: 1px solid var(--border, rgba(0,0,0,.08)); cursor: pointer; color: var(--text, #171717); }
.nb-lang span { opacity: .45; }
.nb-lang span.active { opacity: 1; color: var(--accent, #b8965a); }

.nb-toggle { display: none !important; }

.nb-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.5); opacity: 0; pointer-events: none; transition: opacity .3s; z-index: 9998; }
.nb-backdrop.show { opacity: 1; pointer-events: auto; }

@media (max-width: 900px) {
  .nb-links {
    position: fixed !important;
    top: 0; right: -100%;
    width: min(320px, 85vw);
    height: 100vh;
    background: var(--card, #fff);
    flex-direction: column;
    align-items: stretch;
    padding: 90px 26px 30px;
    gap: 0;
    box-shadow: -20px 0 60px rgba(0,0,0,.25);
    transition: right .35s cubic-bezier(.2,.9,.3,1);
    z-index: 9999;
    overflow-y: auto;
    display: flex !important;
    margin: 0;
  }
  .nb-links.open { right: 0 !important; }
  .nb-links li { width: 100%; border-bottom: 1px solid var(--border, rgba(0,0,0,.08)); }
  .nb-links a { display: block; padding: 16px 0; font-size: 16px; }
  .nb-toggle { display: grid !important; }
  .nb-brand { font-size: 17px; }
  .nb-brand-mark { width: 34px; height: 34px; font-size: 13px; }
}
@media (min-width: 901px) {
  .nb-backdrop { display: none !important; }
}
</style>

<div class="nb-promo">
  <div class="nb-promo-inner">
    <span class="nb-dot"></span>
    <span><?= e(setting('promo_' . current_lang(), setting('promo_en'))) ?></span>
    <span style="opacity:.7;font-size:12px;">• <?= e(setting('promo_sub_' . current_lang(), '')) ?></span>
  </div>
</div>

<nav class="nb-nav" id="mainNavbar">
  <div class="nb-container">
    <div class="nb-inner">
      <a href="<?= url('') ?>" class="nb-brand">
        <span class="nb-brand-mark">AM</span>
        <span class="nb-brand-text">
          <span>Ariyan Mirza</span>
          <small>Wedding Editor</small>
        </span>
      </a>

      <ul class="nb-links" id="nbLinks">
        <li><a href="<?= url('') ?>" class="<?= $current==='index.php'?'active':'' ?>"><?= t('nav_home') ?></a></li>
        <li><a href="<?= url('about.php') ?>" class="<?= $current==='about.php'?'active':'' ?>"><?= t('nav_about') ?></a></li>
        <li><a href="<?= url('services.php') ?>" class="<?= $current==='services.php'?'active':'' ?>"><?= t('nav_services') ?></a></li>
        <li><a href="<?= url('work.php') ?>" class="<?= $current==='work.php'||$current==='album.php'?'active':'' ?>"><?= t('nav_work') ?></a></li>
        <li><a href="<?= url('contact.php') ?>" class="<?= $current==='contact.php'?'active':'' ?>"><?= t('nav_contact') ?></a></li>
      </ul>

      <div class="nb-actions">
        <button type="button" class="nb-lang" onclick="nbSwitchLang('<?= current_lang()==='en'?'bn':'en' ?>')">
          <span class="<?= current_lang()==='en'?'active':'' ?>">EN</span>
          <span style="opacity:.3">|</span>
          <span class="<?= current_lang()==='bn'?'active':'' ?>">বাংলা</span>
        </button>
        <button type="button" class="nb-btn" onclick="nbToggleTheme()" aria-label="Theme">
          <i data-theme-icon class="fa-solid fa-moon"></i>
        </button>
        <button type="button" class="nb-btn nb-toggle" onclick="nbToggleMenu()" aria-label="Menu">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>
    </div>
  </div>
</nav>
<div class="nb-backdrop" id="nbBackdrop" onclick="nbToggleMenu()"></div>

<script>
/* ===== Self-contained navbar JS — no external dependency ===== */
function nbToggleMenu() {
  var menu = document.getElementById('nbLinks');
  var bd = document.getElementById('nbBackdrop');
  if (!menu) { console.warn('nbLinks not found'); return; }
  var willOpen = !menu.classList.contains('open');
  if (willOpen) {
    menu.classList.add('open');
    if (bd) bd.classList.add('show');
    document.body.style.overflow = 'hidden';
  } else {
    menu.classList.remove('open');
    if (bd) bd.classList.remove('show');
    document.body.style.overflow = '';
  }
}

function nbSwitchLang(lang) {
  var url = new URL(window.location.href);
  url.searchParams.set('lang', lang);
  window.location.href = url.toString();
}

function nbToggleTheme() {
  var cur = document.documentElement.getAttribute('data-theme') || 'light';
  var next = cur === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  try { localStorage.setItem('theme', next); } catch (e) {}
  nbUpdateThemeIcon();
}

function nbUpdateThemeIcon() {
  var cur = document.documentElement.getAttribute('data-theme') || 'light';
  var icons = document.querySelectorAll('[data-theme-icon]');
  for (var i = 0; i < icons.length; i++) {
    icons[i].className = cur === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
  }
}

/* Init */
(function() {
  var saved = 'light';
  try { saved = localStorage.getItem('theme') || 'light'; } catch (e) {}
  document.documentElement.setAttribute('data-theme', saved);

  document.addEventListener('DOMContentLoaded', function() {
    nbUpdateThemeIcon();

    // Close on link click
    var links = document.querySelectorAll('#nbLinks a');
    for (var i = 0; i < links.length; i++) {
      links[i].addEventListener('click', function() {
        var menu = document.getElementById('nbLinks');
        var bd = document.getElementById('nbBackdrop');
        if (menu) menu.classList.remove('open');
        if (bd) bd.classList.remove('show');
        document.body.style.overflow = '';
      });
    }

    // Close on escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        var menu = document.getElementById('nbLinks');
        var bd = document.getElementById('nbBackdrop');
        if (menu) menu.classList.remove('open');
        if (bd) bd.classList.remove('show');
        document.body.style.overflow = '';
      }
    });

    // Close on resize to desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth > 900) {
        var menu = document.getElementById('nbLinks');
        var bd = document.getElementById('nbBackdrop');
        if (menu) menu.classList.remove('open');
        if (bd) bd.classList.remove('show');
        document.body.style.overflow = '';
      }
    });
  });
})();
</script>