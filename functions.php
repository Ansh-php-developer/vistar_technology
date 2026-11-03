<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function csrf() { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf() . '">'; }
function csrf_ok() { return isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']); }
function flash($msg = null) {
    if ($msg !== null) { $_SESSION['flash'] = $msg; return ''; }
    $f = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']); return $f;
}
function is_admin() { return !empty($_SESSION['admin_id']); }
function require_admin() { if (!is_admin()) { header('Location: ' . BASE_URL . '/admin/login.php'); exit; } }

function setting($key, $default = '') {
    global $pdo; static $cache = null;
    if ($cache === null) $cache = $pdo->query('SELECT skey, svalue FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR);
    return $cache[$key] ?? $default;
}

function fetch_all($table, $where = '', $order = 'id DESC') {
    global $pdo;
    return $pdo->query("SELECT * FROM $table " . ($where ? "WHERE $where " : '') . "ORDER BY $order")->fetchAll();
}

// Saves an uploaded image to uploads/<folder>/ and returns the relative path, or '' if none/invalid.
function upload_image($field, $folder) {
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return '';
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) return '';
    if ($_FILES[$field]['size'] > 3 * 1024 * 1024) return '';
    if (!@getimagesize($_FILES[$field]['tmp_name'])) return '';
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    $dir = ROOT_PATH . "/uploads/$folder";
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    return move_uploaded_file($_FILES[$field]['tmp_name'], "$dir/$name") ? "uploads/$folder/$name" : '';
}
function img($path, $alt = '') {
    return $path ? '<img src="' . BASE_URL . '/' . e($path) . '" alt="' . e($alt) . '" loading="lazy">' : '';
}
function flip_card($image, $title, $sub, $desc, $btn_label, $btn_href, $extra_class = '') {
    $front = $image
        ? '<img src="' . BASE_URL . '/' . e($image) . '" alt="" loading="lazy">'
        : '<span class="ph" aria-hidden="true"></span>';
    $h  = '<article class="flipcard ' . e($extra_class) . '" tabindex="0">';
    $h .= '<div class="flipcard-inner">';
    $h .= '<div class="face front">' . $front;
    $h .= '<div class="cap"><b>' . e($title) . '</b>' . ($sub !== '' ? '<span>' . e($sub) . '</span>' : '') . '</div></div>';
    $h .= '<div class="face back"><h3>' . e($title) . '</h3>';
    if ($desc !== '') $h .= '<p>' . e($desc) . '</p>';
    if ($btn_href !== '') $h .= '<a class="more" href="' . e($btn_href) . '"' . (preg_match('#^https?://#i', $btn_href) ? ' target="_blank" rel="noopener"' : '') . '>' . e($btn_label) . '</a>';
    $h .= '</div></div></article>';
    return $h;
}
// Send button with plane animation (CSS: .send-btn in assets/css/style.css, JS: bottom of assets/js/script.js).

function send_btn($label = 'Send', $sent = 'Sent') {
    $letters = function ($t) {
        $out = '';
        foreach (mb_str_split($t) as $i => $c) $out .= '<span style="--i:' . $i . '">' . ($c === ' ' ? '&nbsp;' : e($c)) . '</span>';
        return $out;
    };
    $plane = '<svg class="sb-plane" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12 3 4l4 8-4 8z"/></svg>';
    $check = '<svg class="sb-check" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9.5"/><path d="m7.8 12.3 3 3 5.4-6"/></svg>';
    return '<button type="submit" class="send-btn">'
         . '<span class="sb-row sb-idle">' . $plane . '<span class="sb-text">' . $letters($label) . '</span></span>'
         . '<span class="sb-trail" aria-hidden="true"></span>'
         . '<span class="sb-row sb-done" aria-hidden="true">' . $check . '<span class="sb-text">' . $letters($sent) . '</span></span>'
         . '</button>';
}
