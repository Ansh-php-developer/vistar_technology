<?php
require_once __DIR__ . '/functions.php';
$pageTitle = isset($pageTitle) ? $pageTitle . ' | ' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle . setting('site_name', 'Vistar Technology')) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=Source+Sans+3:wght@400;600&family=Inter:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/flip.css">
</head>
<body>
<?php require __DIR__ . '/navbar.php'; ?>
<main>
