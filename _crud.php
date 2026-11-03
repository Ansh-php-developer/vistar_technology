<?php
require_once __DIR__ . '/_layout.php';
/*
 * Generic add/edit/delete screen.
 * $fields: name => [label, type]  (type: text, textarea, number, date, image, or "select:a|b|c")
 * $cols:   columns shown in the list.
 */
function crud($table, $title, $fields, $cols) {
    global $pdo;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_ok()) exit('Invalid form token.');
        $act = $_POST['act'] ?? ''; $id = (int)($_POST['id'] ?? 0);
        if ($act === 'delete' && $id) { $pdo->prepare("DELETE FROM $table WHERE id=?")->execute([$id]); flash('Deleted.'); }
        if ($act === 'save') {
            $vals = [];
            foreach ($fields as $n => $f) {
                if ($f[1] === 'image') { $p = upload_image($n, $table); if ($p) $vals[$n] = $p; }
                else { $v = trim($_POST[$n] ?? ''); $vals[$n] = ($f[1] === 'date' && $v === '') ? null : $v; }
            }
            if ($id) {
                $set = implode(',', array_map(fn($k) => "$k=?", array_keys($vals)));
                $pdo->prepare("UPDATE $table SET $set WHERE id=?")->execute([...array_values($vals), $id]);
            } else {
                $ph = implode(',', array_fill(0, count($vals), '?'));
                $pdo->prepare("INSERT INTO $table (" . implode(',', array_keys($vals)) . ") VALUES ($ph)")->execute(array_values($vals));
            }
            flash('Saved.');
        }
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
    }
    $edit = [];
    if (isset($_GET['edit'])) { $s = $pdo->prepare("SELECT * FROM $table WHERE id=?"); $s->execute([(int)$_GET['edit']]); $edit = $s->fetch() ?: []; }
    $rows = $pdo->query("SELECT * FROM $table ORDER BY id DESC")->fetchAll();
    admin_head($title); ?>
<form class="form" method="post" enctype="multipart/form-data">
  <h2><?= $edit ? 'Edit' : 'Add new' ?></h2>
  <?= csrf_field() ?><input type="hidden" name="act" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
  <?php foreach ($fields as $n => $f): $v = $edit[$n] ?? ''; ?>
    <label><?= e($f[0]) ?>
    <?php if ($f[1] === 'textarea'): ?><textarea name="<?= $n ?>" rows="4"><?= e($v) ?></textarea>
    <?php elseif ($f[1] === 'image'): ?><input type="file" name="<?= $n ?>" accept="image/*"><?= $v ? '<small>Current: ' . e($v) . ' (upload to replace)</small>' : '' ?>
    <?php elseif (str_starts_with($f[1], 'select:')): ?><select name="<?= $n ?>"><?php foreach (explode('|', substr($f[1], 7)) as $o): ?><option<?= $o === $v ? ' selected' : '' ?>><?= e($o) ?></option><?php endforeach; ?></select>
    <?php else: ?><input type="<?= $f[1] ?>" name="<?= $n ?>" value="<?= e($v) ?>"><?php endif; ?></label>
  <?php endforeach; ?>
  <button class="btn" type="submit">Save</button><?php if ($edit): ?> <a href="<?= basename($_SERVER['PHP_SELF']) ?>">Cancel</a><?php endif; ?>
</form>
<div class="scroll"><table><tr><?php foreach ($cols as $c): ?><th><?= e($fields[$c][0] ?? ucfirst($c)) ?></th><?php endforeach; ?><th></th></tr>
<?php foreach ($rows as $r): ?><tr>
  <?php foreach ($cols as $c): ?><td><?= e(mb_strimwidth((string)$r[$c], 0, 60, '...')) ?></td><?php endforeach; ?>
  <td class="act"><a href="?edit=<?= $r['id'] ?>">Edit</a>
    <form method="post" data-confirm="Delete this item?"><?= csrf_field() ?><input type="hidden" name="act" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="link danger">Delete</button></form></td>
</tr><?php endforeach; if (!$rows) echo '<tr><td colspan="' . (count($cols) + 1) . '">Nothing here yet. Add the first item above.</td></tr>'; ?>
</table></div>
<?php admin_foot(); }
