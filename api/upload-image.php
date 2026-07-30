<?php
require __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Not authenticated']);
    exit;
}

if (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'No valid image uploaded']);
    exit;
}

$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Image must be a JPG, PNG, or WEBP file']);
    exit;
}

$filename = 'post_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$dest     = __DIR__ . '/../uploads/posts/' . $filename;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Failed to save the uploaded file']);
    exit;
}

echo json_encode(['ok' => true, 'path' => 'uploads/posts/' . $filename]);
