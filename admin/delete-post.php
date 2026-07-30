<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['csrf_token'] ?? '')) {
    delete_post_record((int)($_POST['id'] ?? 0));
}

header('Location: /admin/posts.php');
exit;
