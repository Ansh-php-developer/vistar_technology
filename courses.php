<?php require __DIR__ . '/_crud.php';
crud('courses', 'Courses', [
  'title' => ['Title', 'text'], 'duration' => ['Duration', 'text'], 'fees' => ['Fee', 'text'],
  'description' => ['Description', 'textarea'], 'image' => ['Image', 'image'],
], ['title', 'duration', 'fees']);
