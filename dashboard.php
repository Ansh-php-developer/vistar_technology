<?php require __DIR__ . '/_layout.php';
$count = fn($t, $w = '1') => (int)$pdo->query("SELECT COUNT(*) FROM $t WHERE $w")->fetchColumn();
$stats = ['New enquiries' => $count('enquiries', "status='new'"), 'Services' => $count('services'), 'Projects' => $count('projects'),
          'Clients' => $count('clients'), 'Courses' => $count('courses'), 'Open jobs' => $count('jobs', "status='open'")];
$recent = $pdo->query('SELECT * FROM enquiries ORDER BY id DESC LIMIT 5')->fetchAll();
admin_head('Dashboard'); ?>
<div class="grid stats"><?php foreach ($stats as $l => $n): ?><div class="card"><strong class="num"><?= $n ?></strong><span><?= e($l) ?></span></div><?php endforeach; ?></div>
<h2>Latest enquiries</h2>
<div class="scroll"><table><tr><th>Date</th><th>Type</th><th>Name</th><th>Message</th></tr>
<?php foreach ($recent as $r): ?><tr><td><?= e(date('j M, H:i', strtotime($r['created_at']))) ?></td><td><?= e($r['type']) ?></td><td><?= e($r['name']) ?></td><td><?= e(mb_strimwidth($r['message'], 0, 70, '...')) ?></td></tr>
<?php endforeach; if (!$recent) echo '<tr><td colspan="4">No enquiries yet.</td></tr>'; ?></table></div>
<p><a href="enquiries.php">View all enquiries</a></p>
<?php admin_foot();
