<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../includes/functions.php';
require_login();

$isFetch = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        if ($isFetch) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Your session expired — please refresh and try again.']);
            exit;
        }
        header('Location: posts.php');
        exit;
    }

    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        delete_post_record($id);
    }

    if ($isFetch) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }
}

header('Location: posts.php');
exit;