<?php
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

$id   = (int)($_GET['id'] ?? 0);
$post = $id ? get_post_by_id($id) : null;

if (!$post) {
    http_response_code(404);
    echo '<p class="text-ink/70 p-10">That journal entry doesn\'t exist or may have been removed.</p>';
    exit;
}

require __DIR__ . '/includes/post-render.php';