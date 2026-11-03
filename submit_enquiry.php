<?php
require_once __DIR__ . '/../includes/functions.php';
$back = ($_POST['return'] ?? '') === 'contact.php' ? 'contact.php' : 'enquiry.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_ok()) { flash('Session expired. Please try again.'); header('Location: ../' . $back); exit; }
if (!empty($_POST['website'])) { header('Location: ../' . $back); exit; } // honeypot

$type = $_POST['type'] ?? 'general';
if (!in_array($type, ['project', 'course', 'job', 'internship', 'general'], true)) $type = 'general';
$name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? ''); $subject = trim($_POST['subject'] ?? ''); $message = trim($_POST['message'] ?? '');

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('Please enter your name, a valid email and a message.');
} else {
    $pdo->prepare('INSERT INTO enquiries (type, name, email, phone, subject, message) VALUES (?,?,?,?,?,?)')
        ->execute([$type, mb_substr($name, 0, 100), mb_substr($email, 0, 150), mb_substr($phone, 0, 20), mb_substr($subject, 0, 150), $message]);
    flash('Thank you. We have your enquiry and will reply soon.');
}
header('Location: ../' . $back);
