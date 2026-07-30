<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../includes/functions.php';
require_login();

$id   = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$post = get_post_by_id($id);

if (!$post) {
    header('Location: /admin/posts.php');
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
        header('Location: /admin/posts.php');
        exit;
    }
}

$page_title   = 'Edit post | ZABIDA admin';
$current_page = 'admin';
require __DIR__ . '/../includes/header.php';
?>
<main id="main-content" class="max-w-2xl mx-auto px-6 py-16">
  <?php require __DIR__ . '/_admin_nav.php'; ?>

  <h1 class="font-serif text-3xl mb-10">Edit post</h1>

  <?php if (!empty($errors)): ?>
    <div class="text-clay text-sm font-medium border border-clay/30 bg-clay/5 px-5 py-4 mb-6">
      <ul class="list-disc list-inside space-y-1"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <form method="POST" class="space-y-7">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
    <div>
      <label for="title" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Title</label>
      <input type="text" id="title" name="title" required value="<?= e($_POST['title'] ?? $post['title']) ?>" class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0">
    </div>
    <div>
      <label for="excerpt" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Excerpt</label>
      <textarea id="excerpt" name="excerpt" rows="2" required class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 resize-none"><?= e($_POST['excerpt'] ?? $post['excerpt']) ?></textarea>
    </div>
    <div>
      <label for="body" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Full text</label>
      <textarea id="body" name="body" rows="6" class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 resize-none"><?= e($_POST['body'] ?? $post['body']) ?></textarea>
    </div>
    <div>
      <label for="published_at" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Publish date</label>
      <input type="date" id="published_at" name="published_at" value="<?= e($_POST['published_at'] ?? $post['published_at']) ?>" class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0">
    </div>
    <button type="submit" class="bg-ink text-paper px-8 py-3 text-sm uppercase tracking-wide hover:bg-clay transition-colors">Save changes</button>
  </form>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
