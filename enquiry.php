<?php $pageTitle = 'Enquiry'; require 'includes/header.php';
$types = ['project' => 'New project', 'course' => 'Course', 'job' => 'Job application', 'internship' => 'Internship', 'general' => 'General'];
$type = $_GET['type'] ?? 'project'; if (!isset($types[$type])) $type = 'project';
$ref = $_GET['ref'] ?? ''; $msg = flash(); ?>
<section class="wrap block narrow"><h1>Send an enquiry</h1>
  <?php if ($msg): ?><p class="notice"><?= e($msg) ?></p><?php endif; ?>
  <form class="form" method="post" action="actions/submit_enquiry.php">
    <?= csrf_field() ?>
    <input type="hidden" name="return" value="enquiry.php">
    <label>Enquiry about
      <select name="type"><?php foreach ($types as $k => $v): ?><option value="<?= $k ?>"<?= $k === $type ? ' selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></label>
    <label>Your name <input name="name" required maxlength="100"></label>
    <label>Email <input type="email" name="email" required maxlength="150"></label>
    <label>Phone <input name="phone" maxlength="20"></label>
    <label>Subject <input name="subject" value="<?= e($ref) ?>" maxlength="150"></label>
    <label>Message <textarea name="message" rows="5" required></textarea></label>
    <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
    <button class="btn" type="submit">Send enquiry</button>
  </form>
</section>
<?php require 'includes/footer.php'; ?>
