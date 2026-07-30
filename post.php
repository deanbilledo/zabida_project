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
  <a href="/index.php#blog" class="font-mono text-xs uppercase tracking-wide text-ink/50 hover:text-clay">&larr; Back to journal</a>

  <?php if (!$post): ?>
    <h1 class="font-serif text-3xl md:text-4xl mt-6 mb-4">Post not found</h1>
    <p class="text-ink/70">That journal entry doesn't exist or may have been removed.</p>
  <?php else: ?>
    <p class="font-mono text-sm text-ink/40 mt-6 mb-3"><?= e(format_post_date($post['published_at'])) ?></p>
    <h1 class="font-serif text-3xl md:text-5xl mb-8 leading-tight"><?= e($post['title']) ?></h1>
    <?php if (!empty($post['image'])): ?>
      <div class="aspect-video w-full rounded-lg bg-gray-100 overflow-hidden border border-ink/10 mb-10">
        <img src="/<?= e($post['image']) ?>" alt="" class="w-full h-full object-cover">
      </div>
    <?php endif; ?>
    <div class="prose prose-lg max-w-none text-ink/80 leading-relaxed whitespace-pre-line"><?= e($post['body']) ?></div>
  <?php endif; ?>
</section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
