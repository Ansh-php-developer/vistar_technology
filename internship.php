<?php $pageTitle = 'Internships'; require 'includes/header.php'; $rows = fetch_all('jobs', "status='open' AND type = 'Internship'"); ?>
<section class="wrap block narrow"><h1>Internships</h1>
  <?php foreach ($rows as $r): ?>
    <article class="card row"><h3><?= e($r['title']) ?></h3>
      <p class="meta"><?= e($r['type']) ?> &middot; <?= e($r['location']) ?><?= $r['last_date'] ? ' &middot; Apply by ' . e(date('j M Y', strtotime($r['last_date']))) : '' ?></p>
      <p><?= e($r['description']) ?></p>
      <a class="btn small" href="enquiry.php?type=internship&amp;ref=<?= urlencode($r['title']) ?>">Apply</a>
    </article>
  <?php endforeach; if (!$rows) echo '<p>No internships open right now.</p>'; ?>
</section>
<?php require 'includes/footer.php'; ?>
