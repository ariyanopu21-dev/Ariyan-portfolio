(function () {
  const saved = localStorage.getItem('theme') || 'light';
  document.documentElement.setAttribute('data-theme', saved);
})();

function toggleTheme() {
  const cur = document.documentElement.getAttribute('data-theme');
  const next = cur === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  localStorage.setItem('theme', next);
  updateThemeIcon();
}
function updateThemeIcon() {
  const cur = document.documentElement.getAttribute('data-theme');
  document.querySelectorAll('[data-theme-icon]').forEach(el => {
    el.className = cur === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
  });
}
function switchLang(lang) {
  const url = new URL(window.location.href);
  url.searchParams.set('lang', lang);
  window.location.href = url.toString();
}
window.addEventListener('scroll', () => {
  const nav = document.querySelector('.navbar');
  if (nav) nav.classList.toggle('scrolled', window.scrollY > 20);
  const st = document.querySelector('.scroll-top');
  if (st) st.classList.toggle('show', window.scrollY > 500);
});
function toggleMobileMenu() {
  const menu = document.querySelector('.nav-links');
  const bd = document.querySelector('.menu-backdrop');
  if (!menu) return;
  menu.classList.toggle('open');
  bd?.classList.toggle('show');
}
document.addEventListener('DOMContentLoaded', () => {
  updateThemeIcon();
  const io = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));
  const loader = document.querySelector('.page-loader');
  if (loader) setTimeout(() => loader.classList.add('hide'), 350);
});

class Lightbox {
  constructor(items) {
    this.items = items; this.idx = 0;
    this.el = document.createElement('div');
    this.el.className = 'lightbox';
    this.el.innerHTML = `
      <button class="lb-btn lb-close" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
      <button class="lb-btn lb-prev" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
      <button class="lb-btn lb-next" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
      <img src="" alt="">
      <div class="lb-counter"></div>`;
    document.body.appendChild(this.el);
    this.img = this.el.querySelector('img');
    this.counter = this.el.querySelector('.lb-counter');
    this.el.querySelector('.lb-close').onclick = () => this.close();
    this.el.querySelector('.lb-prev').onclick = (e) => { e.stopPropagation(); this.prev(); };
    this.el.querySelector('.lb-next').onclick = (e) => { e.stopPropagation(); this.next(); };
    this.el.addEventListener('click', (e) => { if (e.target === this.el) this.close(); });
    document.addEventListener('keydown', (e) => {
      if (!this.el.classList.contains('show')) return;
      if (e.key === 'Escape') this.close();
      if (e.key === 'ArrowLeft') this.prev();
      if (e.key === 'ArrowRight') this.next();
    });
    let sx = 0;
    this.el.addEventListener('touchstart', e => sx = e.touches[0].clientX);
    this.el.addEventListener('touchend', e => {
      const dx = e.changedTouches[0].clientX - sx;
      if (Math.abs(dx) > 50) dx < 0 ? this.next() : this.prev();
    });
  }
  open(i) { this.idx = i; this.show(); this.el.classList.add('show'); document.body.style.overflow = 'hidden'; }
  close() { this.el.classList.remove('show'); document.body.style.overflow = ''; }
  show() { this.img.src = this.items[this.idx].src; this.counter.textContent = (this.idx + 1) + ' / ' + this.items.length; }
  next() { this.idx = (this.idx + 1) % this.items.length; this.show(); }
  prev() { this.idx = (this.idx - 1 + this.items.length) % this.items.length; this.show(); }
}
document.addEventListener('DOMContentLoaded', () => {
  const gallery = document.querySelectorAll('[data-lightbox]');
  if (!gallery.length) return;
  const items = Array.from(gallery).map(el => ({ src: el.dataset.src || el.querySelector('img')?.src }));
  const lb = new Lightbox(items);
  gallery.forEach((el, i) => el.addEventListener('click', () => lb.open(i)));
});
function showToast(msg) {
  let wrap = document.querySelector('.toast-wrap');
  if (!wrap) { wrap = document.createElement('div'); wrap.className = 'toast-wrap'; document.body.appendChild(wrap); }
  const t = document.createElement('div'); t.className = 'toast'; t.textContent = msg;
  wrap.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3200);
}