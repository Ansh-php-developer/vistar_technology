<?php $pageTitle = 'Portfolio'; require 'includes/header.php'; $rows = fetch_all('projects'); ?>
<section class="wrap block"><h1>Portfolio</h1>
  <div class="flip-grid">
  <?php foreach ($rows as $r): ?>
    <?php $sub = $r['category'] . ($r['client_name'] ? ' for ' . $r['client_name'] : ''); ?>
    <?= $r['url'] ? flip_card($r['image'], $r['title'], $sub, $r['description'], 'Visit site', $r['url']) : flip_card($r['image'], $r['title'], $sub, $r['description'], 'Explore More', 'enquiry.php?type=project&ref=' . urlencode($r['title'])) ?>
  <?php endforeach; if (!$rows) echo '<p>No projects yet.</p>'; ?>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
