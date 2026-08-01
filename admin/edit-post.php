<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../includes/functions.php';
require_login();

$isFetch = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';

$id   = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$post = get_post_by_id($id);

if (!$post) {
    if ($isFetch) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'message' => 'Post not found.']);
        exit;
    }
    header('Location: posts.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Your session expired — please try again.';
    }
    $title   = trim($_POST['title'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $body    = trim($_POST['body'] ?? '');
    $date    = $_POST['published_at'] ?? $post['published_at'];

    if ($title === '')   $errors[] = 'Title is required.';
    if ($excerpt === '') $errors[] = 'Excerpt is required.';

    if (empty($errors)) {
        update_post_record($id, compact('title', 'excerpt', 'body') + ['published_at' => $date]);

        if ($isFetch) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit;
        }

        header('Location: posts.php');
        exit;
    }

    // Validation failed — merge submitted values back in for re-display
    $post = array_merge($post, compact('title', 'excerpt', 'body') + ['published_at' => $date]);

    if ($isFetch) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'errors' => $errors]);
        exit;
    }
}

// Fragment request (modal GET, or JSON-consuming client re-rendering after error) — return just the form
if ($isFetch) {
    header('Content-Type: text/html; charset=utf-8');
    require __DIR__ . '/../includes/edit-post-form.php';
    exit;
}

$page_title   = 'Edit post | ZABIDA admin';
$current_page = 'admin';
require __DIR__ . '/../includes/header.php';
?>
<main id="main-content" class="max-w-2xl mx-auto px-6 py-16">
  <?php require __DIR__ . '/_admin_nav.php'; ?>
  <h1 class="font-serif text-3xl mb-10">Edit post</h1>
  <?php require __DIR__ . '/../includes/edit-post-form.php'; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>