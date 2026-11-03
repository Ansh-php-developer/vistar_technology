<?php require __DIR__ . '/_crud.php';
crud('projects', 'Projects', [
  'title' => ['Title', 'text'], 'category' => ['Category', 'text'], 'client_name' => ['Client', 'text'],
  'description' => ['Description', 'textarea'], 'url' => ['Live URL', 'text'], 'image' => ['Image', 'image'],
], ['title', 'category', 'client_name']);
