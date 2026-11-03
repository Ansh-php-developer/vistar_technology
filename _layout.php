<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
function admin_head($title) {
    $menu = ['dashboard' => 'Dashboard', 'services' => 'Services', 'projects' => 'Projects', 'clients' => 'Clients', 'courses' => 'Courses',
             'jobs' => 'Jobs', 'enquiries' => 'Enquiries', 'settings' => 'Settings'];
    $cur = basename($_SERVER['SCRIPT_NAME'], '.php'); $msg = flash(); ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> | Admin</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css"></head>
<body class="admin"><aside class="side"><strong>Admin</strong>
<?php foreach ($menu as $f => $l): ?><a href="<?= $f ?>.php"<?= $cur === $f ? ' class="on"' : '' ?>><?= $l ?></a><?php endforeach; ?>
<a href="<?= BASE_URL ?>/index.php" target="_blank">View site</a><a href="logout.php">Log out</a></aside>
<section class="panel"><h1><?= e($title) ?></h1><?php if ($msg): ?><p class="notice"><?= e($msg) ?></p><?php endif;
}
function admin_foot() { echo '</section><script src="' . BASE_URL . '/assets/js/script.js"></script></body></html>'; }
