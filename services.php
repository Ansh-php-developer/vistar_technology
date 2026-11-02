<?php $pageTitle = 'Services'; require 'includes/header.php'; $rows = fetch_all('services', "status='active'", 'id ASC'); ?>
<section class="wrap block"><h1>Services</h1>
  <div class="flip-grid">
  <?php foreach ($rows as $r): ?>
    <?= flip_card($r['image'], $r['title'], '', $r['description'], 'Explore More', 'enquiry.php?type=project&ref=' . urlencode($r['title'])) ?>
  <?php endforeach; if (!$rows) echo '<p>No services listed yet.</p>'; ?>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
