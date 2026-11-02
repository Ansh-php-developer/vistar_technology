<?php $pageTitle = 'Contact'; require 'includes/header.php'; $msg = flash(); ?>
<section class="wrap block split">
  <div><h1>Contact us</h1>
    <p><?= e(setting('address')) ?></p>
    <p>Phone: <?= e(setting('phone')) ?></p>
    <p>Email: <?= e(setting('email')) ?></p>
    <p>Hours: <?= e(setting('hours', 'Mon-Sat, 10am-6pm')) ?></p>
  </div>
  <div>
    <?php if ($msg): ?><p class="notice"><?= e($msg) ?></p><?php endif; ?>
    <form class="form" method="post" action="actions/submit_enquiry.php">
      <?= csrf_field() ?>
      <input type="hidden" name="type" value="general">
      <input type="hidden" name="return" value="contact.php">
      <label>Your name <input name="name" required maxlength="100"></label>
      <label>Email <input type="email" name="email" required maxlength="150"></label>
      <label>Phone <input name="phone" maxlength="20"></label>
      <label>Message <textarea name="message" rows="5" required></textarea></label>
      <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
      <button class="btn" type="submit">Send message</button>
    </form>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
