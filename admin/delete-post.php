<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (csrf_check($_POST['csrf_token'] ?? '')) {
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id > 0) {
            delete_post_record($id);
        }
    }
}

// FIXED: Changed /admin/posts.php to relative path posts.php
header('Location: posts.php');
exit;