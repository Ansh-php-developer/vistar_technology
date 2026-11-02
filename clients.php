<? php require 'includes/header.php'; ?>
<?php $pageTitle = 'Clients'; require 'includes/header.php'; $rows = fetch_all('clients'); ?>
<section class="wrap block"><h1>Our clients</h1>
  <div class="flip-grid">
  <?php foreach ($rows as $r): ?>
    <?= flip_card($r['logo'], $r['name'], '', 'Proud to work with ' . $r['name'] . '.', 'Start a project', 'enquiry.php?type=project', 'logo') ?>
  <?php endforeach; if (!$rows) echo '<p>No clients listed yet.</p>'; ?>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
