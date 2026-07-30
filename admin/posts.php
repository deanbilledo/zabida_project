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
    <!-- FIXED: Changed /admin/create-post.php to relative create-post.php -->
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
          <!-- FIXED: Reached post.php in root directory using ../post.php -->
          <a href="../post.php?id=<?= (int)$post['id'] ?>" target="_blank" class="border-b border-ink/40 hover:text-clay hover:border-clay">View</a>
          
          <!-- FIXED: Changed /admin/edit-post.php to relative edit-post.php -->
          <a href="edit-post.php?id=<?= (int)$post['id'] ?>" class="border-b border-ink/40 hover:text-clay hover:border-clay">Edit</a>
          
          <!-- FIXED: Changed action="/admin/delete-post.php" to relative action="delete-post.php" -->
          <form action="delete-post.php" method="POST" onsubmit="return confirm('Delete this post? This cannot be undone.');">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
            <button type="submit" class="border-b border-clay/60 text-clay hover:border-clay">Delete</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if (empty($posts)): ?><p class="text-ink/50 py-6">No posts yet.</p><?php endif; ?>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>