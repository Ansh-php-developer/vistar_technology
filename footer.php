</main>
<footer class="site-footer">
  <div class="wrap foot">
    <div>
      <strong><?= e(setting('site_name', 'Vistar Technology')) ?></strong>
      <p><?= e(setting('tagline', 'Websites, software and training for growing businesses.')) ?></p>
    </div>
    <div>
      <p><?= e(setting('address')) ?></p>
      <p><?= e(setting('phone')) ?> &middot; <?= e(setting('email')) ?></p>
    </div>
  </div>
  <p class="copy">&copy; <?= date('Y') ?> <?= e(setting('site_name', 'Vistar Technology')) ?>. All rights reserved.</p>
</footer>
<script src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>
