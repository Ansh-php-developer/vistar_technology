<?php $pageTitle = 'Home'; require 'includes/header.php';
$services = array_slice(fetch_all('services', "status='active'", 'id ASC'), 0, 3);
$projects = array_slice(fetch_all('projects'), 0, 3); ?>
<section class="hero">
  <div class="wrap">
    <h1><?= e(setting('hero_title', 'We build software that your business actually uses.')) ?></h1>
    <p class="lead"><?= e(setting('hero_text', 'Websites, web apps and training from a small team that answers its phone.')) ?></p>
    <a class="btn" href="enquiry.php">Start a project</a>
    <a class="btn ghost" href="portfolio.php">See our work</a>
  </div>
</section>
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
<?php require 'includes/footer.php'; ?>
