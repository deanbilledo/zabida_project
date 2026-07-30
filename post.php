<?php
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/config/database.php';

$id   = (int)($_GET['id'] ?? 0);
$post = $id ? get_post_by_id($id) : null;

$page_title   = $post ? $post['title'] . ' | ZABIDA Journal' : 'Post not found | ZABIDA';
$current_page = 'index';

require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
<section class="max-w-3xl mx-auto px-6 py-20 md:py-28">
  <a href="/zabida_project/activities-post.php" class="font-mono text-xs uppercase tracking-wide text-ink/50 hover:text-clay">&larr; Back to journal</a>

  <?php if (!$post): ?>
    <h1 class="font-serif text-3xl md:text-4xl mt-6 mb-4">Post not found</h1>
    <p class="text-ink/70">That journal entry doesn't exist or may have been removed.</p>
  <?php else: ?>
    <div class="mt-6">
      <?php require __DIR__ . '/includes/post-render.php'; ?>
    </div>
  <?php endif; ?>
</section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>