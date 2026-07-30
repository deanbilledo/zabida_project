<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Not authenticated']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$id    = (int)($input['id'] ?? 0);

if (!delete_post_record($id)) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Post not found']);
    exit;
}

echo json_encode(['ok' => true]);
