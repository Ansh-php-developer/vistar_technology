<?php $pageTitle = 'Courses'; require 'includes/header.php'; $rows = fetch_all('courses'); ?>
<section class="wrap block"><h1>Training courses</h1>
  <div class="flip-grid">
  <?php foreach ($rows as $r): ?>
    <?php $sub = $r['duration'] . ($r['fees'] !== '' ? ($r['duration'] !== '' ? ' Â· ' : '') . 'Fee: ' . $r['fees'] : ''); ?>
    <?= flip_card($r['image'], $r['title'], $sub, $r['description'], 'Enquire Now', 'enquiry.php?type=course&ref=' . urlencode($r['title'])) ?>
  <?php endforeach; if (!$rows) echo '<p>No courses open right now.</p>'; ?>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
