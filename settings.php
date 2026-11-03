<?php require __DIR__ . '/_layout.php';
$keys = ['site_name' => ['Company name', 'text'], 'tagline' => ['Footer tagline', 'text'], 'hero_title' => ['Home page headline', 'text'],
         'hero_text' => ['Home page intro', 'textarea'], 'about_text' => ['About page text', 'textarea'], 'address' => ['Address', 'text'],
         'phone' => ['Phone', 'text'], 'email' => ['Email', 'text'], 'hours' => ['Opening hours', 'text']];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) exit('Invalid form token.');
    $st = $pdo->prepare('INSERT INTO settings (skey, svalue) VALUES (?,?) ON DUPLICATE KEY UPDATE svalue=VALUES(svalue)');
    foreach ($keys as $k => $f) $st->execute([$k, trim($_POST[$k] ?? '')]);
    if (!empty($_POST['new_password'])) {
        if (strlen($_POST['new_password']) < 8) { flash('Password must be at least 8 characters. Other settings were saved.'); header('Location: settings.php'); exit; }
        $pdo->prepare('UPDATE admins SET password_hash=? WHERE id=?')->execute([password_hash($_POST['new_password'], PASSWORD_DEFAULT), $_SESSION['admin_id']]);
    }
    flash('Settings saved.'); header('Location: settings.php'); exit;
}
admin_head('Settings'); ?>
<form class="form" method="post"><?= csrf_field() ?>
<?php foreach ($keys as $k => $f): ?><label><?= e($f[0]) ?>
<?php if ($f[1] === 'textarea'): ?><textarea name="<?= $k ?>" rows="4"><?= e(setting($k)) ?></textarea><?php else: ?><input name="<?= $k ?>" value="<?= e(setting($k)) ?>"><?php endif; ?></label>
<?php endforeach; ?>
<label>New admin password (leave blank to keep current) <input type="password" name="new_password" autocomplete="new-password"></label>
<button class="btn" type="submit">Save settings</button></form>
<?php admin_foot();
