<?php
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../config/database.php';

header('Content-Type: application/json');
echo json_encode(['ok' => true, 'posts' => get_all_posts()]);
