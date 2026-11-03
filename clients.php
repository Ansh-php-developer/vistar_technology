<?php require __DIR__ . '/_crud.php';
crud('clients', 'Clients', ['name' => ['Client name', 'text'], 'logo' => ['Logo', 'image']], ['name']);
