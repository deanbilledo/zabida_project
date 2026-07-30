<?php
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$id   = (int)($_GET['id'] ?? 0);
$post = $id ? get_post_by_id($id) : null;

if (!$post) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Post not found']);
    exit;
}

echo json_encode(['ok' => true, 'post' => $post]);
