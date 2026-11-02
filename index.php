<?php $pageTitle = 'Home'; require 'includes/header.php';
$services = array_slice(fetch_all('services', "status='active'", 'id ASC'), 0, 3);
$allProjects = fetch_all('projects');
$projects = array_slice($allProjects, 0, 3);
$slides = array_slice($allProjects, 0, 5); // projects shown in the slider
?>
<style>
/* ---------- Slider (scoped to .slider) ---------- */
.slider{position:relative;overflow:hidden;border-radius:12px;background:#10151c;color:#fff;outline-offset:3px}
.slider-track{display:flex;transition:transform .55s cubic-bezier(.65,.05,.25,1);will-change:transform;touch-action:pan-y}
.slide{flex:0 0 100%;min-width:100%;position:relative;min-height:380px;display:grid;align-items:end}
.slide .slide-img{position:absolute;inset:0}
.slide .slide-img img{width:100%;height:100%;object-fit:cover;display:block}
.slide::after{content:"";position:absolute;inset:0;background:linear-gradient(to top,rgba(8,12,18,.88) 0%,rgba(8,12,18,.25) 55%,rgba(8,12,18,0) 100%)}
.slide-body{position:relative;z-index:1;padding:2rem 2rem 3.5rem;max-width:42rem}
.slide-body h3{margin:0 0 .35rem;font-size:clamp(1.4rem,3vw,2.1rem);color:#fff}
.slide-body p{margin:0;opacity:.85}
.slider-btn{position:absolute;top:50%;transform:translateY(-50%);z-index:2;width:46px;height:46px;border-radius:50%;border:0;cursor:pointer;
  background:rgba(255,255,255,.92);color:#10151c;font-size:1.4rem;line-height:1;display:grid;place-items:center;transition:background .2s,transform .2s}
.slider-btn:hover{background:#fff;transform:translateY(-50%) scale(1.07)}
.slider-btn:focus-visible,.slider-dot:focus-visible,.slider-pause:focus-visible{outline:3px solid #ffb703;outline-offset:2px}
.slider-btn.prev{left:1rem}.slider-btn.next{right:1rem}
.slider-dots{position:absolute;left:0;right:0;bottom:1rem;z-index:2;display:flex;justify-content:center;gap:.5rem}
.slider-dot{width:11px;height:11px;border-radius:99px;border:0;padding:0;background:rgba(255,255,255,.5);cursor:pointer;transition:width .3s,background .3s}
.slider-dot[aria-current="true"]{width:30px;background:#fff}
.slider-pause{position:absolute;top:1rem;right:1rem;z-index:2;border:0;border-radius:99px;padding:.35rem .8rem;cursor:pointer;
  background:rgba(0,0,0,.55);color:#fff;font:inherit;font-size:.85rem}
.slider-progress{position:absolute;left:0;bottom:0;height:3px;width:0;background:#ffb703;z-index:3}
.slider-count{position:absolute;top:1rem;left:1rem;z-index:2;background:rgba(0,0,0,.55);color:#fff;border-radius:99px;padding:.35rem .8rem;font-size:.85rem}
.slider-empty{padding:2rem;text-align:center}
@media (max-width:600px){.slide{min-height:320px}.slider-btn{width:38px;height:38px}.slide-body{padding:1.25rem 1.25rem 3.25rem}}
@media (prefers-reduced-motion:reduce){.slider-track{transition:none}.slider-progress{display:none}}
/* ---------- Hero with video background ---------- */
.hero.hero-video{position:relative;overflow:hidden;isolation:isolate;background:#e9e6f3 url('assets/video/hero-poster.jpg') center/cover no-repeat;color:var(--ink);height:431px;display:flex;align-items:center;padding:0}
.hero-video .hero-bg{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:0;margin:0;z-index:-2}
.hero-video::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,rgba(245,243,252,.8) 0%,rgba(245,243,252,.5) 35%,rgba(245,243,252,0) 65%)}
.hero-video h1{color:var(--ink)}
.hero-video .lead{color:#3b4a5a}
.hero-video .btn.ghost{color:var(--ink);border-color:var(--ink)}
@media (max-width:700px){.hero.hero-video{height:auto;min-height:431px;padding:48px 0}.hero-video::before{background:rgba(245,243,252,.78)}}
@media (prefers-reduced-motion:reduce){.hero-video .hero-bg{display:none}}
</style>

<section class="hero hero-video">
  <video class="hero-bg" autoplay muted loop playsinline preload="auto" poster="assets/video/hero-poster.jpg" aria-hidden="true" tabindex="-1">
    <source src="assets/video/hero-bg.mp4" type="video/mp4">
  </video>
  <div class="wrap">
    <h1><?= e(setting('hero_title', 'We build software that your business actually uses.')) ?></h1>
    <p class="lead"><?= e(setting('hero_text', 'Websites, web apps and training from a small team that answers its phone.')) ?></p>
    <a class="btn" href="enquiry.php">Start a project</a>
    <a class="btn ghost" href="portfolio.php">See our work</a>
  </div>
</section>

<?php if ($slides): ?>
<section class="wrap block">
  <h2>Featured work</h2>
  <div class="slider" id="featuredSlider" role="region" aria-roledescription="carousel" aria-label="Featured projects" tabindex="0" data-interval="5000">
    <div class="slider-track">
      <?php foreach ($slides as $i => $p): ?>
        <div class="slide" role="group" aria-roledescription="slide" aria-label="<?= $i + 1 ?> of <?= count($slides) ?>">
          <div class="slide-img"><?= img($p['image'], $p['title']) ?></div>
          <div class="slide-body">
            <h3><?= e($p['title']) ?></h3>
            <p><?= e($p['category']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <span class="slider-count" aria-hidden="true">1 / <?= count($slides) ?></span>
    <button class="slider-pause" type="button" aria-label="Pause slideshow">Pause</button>
    <?php if (count($slides) > 1): ?>
      <button class="slider-btn prev" type="button" aria-label="Previous slide">&#8249;</button>
      <button class="slider-btn next" type="button" aria-label="Next slide">&#8250;</button>
      <div class="slider-dots">
        <?php foreach ($slides as $i => $p): ?>
          <button class="slider-dot" type="button" aria-label="Go to slide <?= $i + 1 ?>" <?= $i === 0 ? 'aria-current="true"' : '' ?>></button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="slider-progress"></div>
  </div>
</section>
<?php endif; ?>

<section class="wrap block">
  <h2>What we do</h2>
  <div class="grid">
    <?php foreach ($services as $s): ?>
      <article class="card"><?= img($s['image'], $s['title']) ?><h3><?= e($s['title']) ?></h3><p><?= e($s['description']) ?></p></article>
    <?php endforeach; ?>
  </div>
  <p><a href="services.php">All services</a></p>
</section>
<section class="wrap block">
  <h2>Recent projects</h2>
  <div class="grid">
    <?php foreach ($projects as $p): ?>
      <article class="card"><?= img($p['image'], $p['title']) ?><h3><?= e($p['title']) ?></h3><p><?= e($p['category']) ?></p></article>
    <?php endforeach; ?>
  </div>
</section>

<script>
(function () {
  var root = document.getElementById('featuredSlider');
  if (!root) return;
  var track = root.querySelector('.slider-track');
  var slides = root.querySelectorAll('.slide');
  var dots = root.querySelectorAll('.slider-dot');
  var count = root.querySelector('.slider-count');
  var bar = root.querySelector('.slider-progress');
  var pauseBtn = root.querySelector('.slider-pause');
  var total = slides.length, index = 0, timer = null;
  var interval = parseInt(root.dataset.interval, 10) || 5000;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var userPaused = reduce, hovering = false;

  function go(i) {
    index = (i + total) % total;
    track.style.transform = 'translateX(' + (-100 * index) + '%)';
    dots.forEach(function (d, n) {
      if (n === index) d.setAttribute('aria-current', 'true'); else d.removeAttribute('aria-current');
    });
    count.textContent = (index + 1) + ' / ' + total;
    slides.forEach(function (s, n) { s.setAttribute('aria-hidden', n === index ? 'false' : 'true'); });
    restart();
  }

  function animateBar() {
    bar.style.transition = 'none'; bar.style.width = '0';
    if (userPaused || hovering || total < 2) return;
    void bar.offsetWidth;
    bar.style.transition = 'width ' + interval + 'ms linear'; bar.style.width = '100%';
  }
  function stop() { clearTimeout(timer); timer = null; bar.style.transition = 'none'; bar.style.width = '0'; }
  function restart() {
    stop();
    if (userPaused || hovering || total < 2) return;
    animateBar();
    timer = setTimeout(function () { go(index + 1); }, interval);
  }

  var prev = root.querySelector('.prev'), next = root.querySelector('.next');
  if (prev) prev.addEventListener('click', function () { go(index - 1); });
  if (next) next.addEventListener('click', function () { go(index + 1); });
  dots.forEach(function (d, n) { d.addEventListener('click', function () { go(n); }); });

  pauseBtn.addEventListener('click', function () {
    userPaused = !userPaused;
    pauseBtn.textContent = userPaused ? 'Play' : 'Pause';
    pauseBtn.setAttribute('aria-label', userPaused ? 'Play slideshow' : 'Pause slideshow');
    restart();
  });
  if (userPaused) { pauseBtn.textContent = 'Play'; pauseBtn.setAttribute('aria-label', 'Play slideshow'); }

  root.addEventListener('mouseenter', function () { hovering = true; restart(); });
  root.addEventListener('mouseleave', function () { hovering = false; restart(); });
  root.addEventListener('focusin', function () { hovering = true; restart(); });
  root.addEventListener('focusout', function () { hovering = false; restart(); });

  root.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft') { go(index - 1); e.preventDefault(); }
    else if (e.key === 'ArrowRight') { go(index + 1); e.preventDefault(); }
  });

  // Touch / mouse swipe
  var startX = null, startY = null;
  root.addEventListener('pointerdown', function (e) {
    if (e.target.closest('button')) return;
    startX = e.clientX; startY = e.clientY;
  });
  root.addEventListener('pointerup', function (e) {
    if (startX === null) return;
    var dx = e.clientX - startX, dy = e.clientY - startY;
    startX = null;
    if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy)) go(index + (dx < 0 ? 1 : -1));
  });
  root.addEventListener('pointercancel', function () { startX = null; });

  document.addEventListener('visibilitychange', function () { document.hidden ? stop() : restart(); });

  go(0);
})();
</script>
<?php require 'includes/footer.php'; ?>S 