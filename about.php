<?php $pageTitle = 'About'; require 'includes/header.php'; ?>
<section class="wrap block narrow">
  <h1>About <?= e(setting('site_name', 'Vistar Technology')) ?></h1>
  <p><?= nl2br(e(setting('about_text', 'Add your company story in Admin > Settings.'))) ?></p>
</section>
<?php require 'includes/footer.php'; ?>
