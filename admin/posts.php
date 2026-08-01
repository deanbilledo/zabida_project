<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../includes/functions.php';
require_login();

$posts = get_all_posts();

$page_title   = 'Posts | ZABIDA admin';
$current_page = 'admin';
require __DIR__ . '/../includes/header.php';
?>
<main id="main-content" class="max-w-5xl mx-auto px-6 py-16">
  <?php require __DIR__ . '/_admin_nav.php'; ?>

  <div class="flex items-center justify-between mb-10">
    <h1 class="font-serif text-3xl">Journal posts</h1>
    <a href="create-post.php" class="bg-ink text-paper px-5 py-2.5 text-sm uppercase tracking-wide hover:bg-clay transition-colors">New post</a>
  </div>

  <div class="divide-y divide-ink/10">
    <?php foreach ($posts as $post): ?>
      <div class="flex flex-wrap items-center justify-between gap-4 py-5">
        <div>
          <p class="font-serif text-lg"><?= e($post['title']) ?></p>
          <p class="font-mono text-xs text-ink/40"><?= e($post['published_at']) ?> &middot; <?= e($post['source']) ?></p>
        </div>
        <div class="flex gap-4 text-sm uppercase tracking-wide">
          <a href="../post.php?id=<?= (int)$post['id'] ?>" target="_blank"
             class="js-view-link border-b border-ink/40 hover:text-clay hover:border-clay"
             data-post-id="<?= (int)$post['id'] ?>">View</a>

          <a href="edit-post.php?id=<?= (int)$post['id'] ?>"
             class="js-edit-link border-b border-ink/40 hover:text-clay hover:border-clay"
             data-post-id="<?= (int)$post['id'] ?>">Edit</a>

          <button type="button"
                  class="js-delete-btn border-b border-clay/60 text-clay hover:border-clay"
                  data-post-id="<?= (int)$post['id'] ?>"
                  data-post-title="<?= e($post['title']) ?>"
                  data-csrf="<?= e(csrf_token()) ?>">
            Delete
          </button>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if (empty($posts)): ?><p class="text-ink/50 py-6">No posts yet.</p><?php endif; ?>
  </div>
</main>

<?php require __DIR__ . '/../includes/admin-post-modal.php'; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>