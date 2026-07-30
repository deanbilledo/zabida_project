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

if (!$id || !get_post_by_id($id)) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Post not found']);
    exit;
}

$fields = array_filter([
    'title'        => isset($input['title']) ? trim($input['title']) : null,
    'excerpt'      => isset($input['excerpt']) ? trim($input['excerpt']) : null,
    'body'         => isset($input['body']) ? trim($input['body']) : null,
    'image'        => $input['image'] ?? null,
    'published_at' => $input['published_at'] ?? null,
], fn($v) => $v !== null);

update_post_record($id, $fields);
echo json_encode(['ok' => true, 'post' => get_post_by_id($id)]);
