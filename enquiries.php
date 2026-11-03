<?php require __DIR__ . '/_layout.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) exit('Invalid form token.');
    $id = (int)($_POST['id'] ?? 0);
    if (($_POST['act'] ?? '') === 'delete') $pdo->prepare('DELETE FROM enquiries WHERE id=?')->execute([$id]);
    if (($_POST['act'] ?? '') === 'status' && in_array($_POST['status'] ?? '', ['new', 'contacted', 'closed'], true))
        $pdo->prepare('UPDATE enquiries SET status=? WHERE id=?')->execute([$_POST['status'], $id]);
    header('Location: enquiries.php'); exit;
}
$rows = $pdo->query('SELECT * FROM enquiries ORDER BY id DESC')->fetchAll();
admin_head('Enquiries'); ?>
<div class="scroll"><table><tr><th>Date</th><th>Type</th><th>Contact</th><th>Message</th><th>Status</th><th></th></tr>
<?php foreach ($rows as $r): ?><tr>
<td><?= e(date('j M Y, H:i', strtotime($r['created_at']))) ?></td><td><?= e($r['type']) ?></td>
<td><strong><?= e($r['name']) ?></strong><br><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a><br><?= e($r['phone']) ?></td>
<td><?= $r['subject'] ? '<strong>' . e($r['subject']) . '</strong><br>' : '' ?><?= nl2br(e($r['message'])) ?></td>
<td><form method="post"><?= csrf_field() ?><input type="hidden" name="act" value="status"><input type="hidden" name="id" value="<?= $r['id'] ?>">
<select name="status" data-autosubmit><?php foreach (['new', 'contacted', 'closed'] as $s): ?><option<?= $s === $r['status'] ? ' selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select></form></td>
<td class="act"><form method="post" data-confirm="Delete this enquiry?"><?= csrf_field() ?><input type="hidden" name="act" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="link danger">Delete</button></form></td>
</tr><?php endforeach; if (!$rows) echo '<tr><td colspan="6">No enquiries yet.</td></tr>'; ?></table></div>
<?php admin_foot();
