<?php
// Edit these for your server (XAMPP defaults shown).
define('DB_HOST', 'localhost');
define('DB_NAME', 'vistar_technology');
define('DB_USER', 'root');
define('DB_PASS', '');
// URL path of the project folder, e.g. '/vistar_technology' or '' if at web root.
define('BASE_URL', '/vistar_technology');
define('ROOT_PATH', dirname(__DIR__));

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    exit('Database connection failed. Check config/db.php and import database/vistar_technology.sql.');
}
