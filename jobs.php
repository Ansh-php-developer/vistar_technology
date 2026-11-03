<?php require __DIR__ . '/_crud.php';
crud('jobs', 'Jobs and internships', [
  'title' => ['Title', 'text'], 'type' => ['Type', 'select:Full-time|Part-time|Internship'], 'location' => ['Location', 'text'],
  'description' => ['Description', 'textarea'], 'last_date' => ['Apply by', 'date'], 'status' => ['Status', 'select:open|closed'],
], ['title', 'type', 'status']);
