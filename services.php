<?php require __DIR__ . '/_crud.php';
crud('services', 'Services', [
  'title' => ['Title', 'text'], 'description' => ['Description', 'textarea'],
  'image' => ['Image', 'image'], 'status' => ['Status', 'select:active|hidden'],
], ['title', 'status']);
