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

$title   = trim($input['title'] ?? '');
$excerpt = trim($input['excerpt'] ?? '');

if ($title === '' || $excerpt === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'title and excerpt are required']);
    exit;
}

$post = create_post_record([
    'title'        => $title,
    'excerpt'      => $excerpt,
    'body'         => trim($input['body'] ?? $excerpt),
    'image'        => $input['image'] ?? null,
    'published_at' => $input['published_at'] ?? date('Y-m-d'),
    'source'       => 'manual',
]);

echo json_encode(['ok' => true, 'post' => $post]);
