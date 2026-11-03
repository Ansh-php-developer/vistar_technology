<?php
$links = ['index' => 'Home', 'about' => 'About', 'services' => 'Services', 'portfolio' => 'Portfolio', 'clients' => 'Clients',
          'courses' => 'Courses', 'jobs' => 'Jobs', 'internship' => 'Internships', 'contact' => 'Contact'];
$current = basename($_SERVER['SCRIPT_NAME'], '.php');
?>
<header class="site-header">
  <div class="wrap bar">
    <a class="brand" href="<?= BASE_URL ?>/index.php"><?= e(setting('site_name', 'Vistar Technology')) ?></a>
    <button class="menu-btn" aria-label="Open menu" aria-expanded="false">Menu</button>
    <nav class="nav" aria-label="Main">
      <span class="nav-ind" aria-hidden="true"></span>
      <?php foreach ($links as $f => $label): ?>
        <a href="<?= BASE_URL ?>/<?= $f ?>.php"<?= $current === $f ? ' class="on"' : '' ?>><?= $label ?></a>
      <?php endforeach; ?>
      <a class="btn small" href="<?= BASE_URL ?>/enquiry.php">Get a quote</a>
    </nav>
  </div>
</header>
