<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/functions.php';
require_login();

$posts = get_all_posts();

$page_title   = 'Dashboard | ZABIDA admin';
$current_page = 'admin';
require __DIR__ . '/../includes/header.php';
?>
<main id="main-content" class="max-w-5xl mx-auto px-6 py-16">
  <?php require __DIR__ . '/_admin_nav.php'; ?>

  <h1 class="font-serif text-3xl mb-10">Dashboard</h1>

  <div class="grid sm:grid-cols-3 gap-6 mb-14">
    <div class="border border-ink/10 p-6">
      <p class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-2">Journal posts</p>
      <p class="font-serif text-4xl"><?= count($posts) ?></p>
    </div>
    <div class="border border-ink/10 p-6">
      <p class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-2">Member NGOs</p>
      <p class="font-serif text-4xl">4</p>
    </div>
    <div class="border border-ink/10 p-6">
      <p class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-2">DB connection</p>
      <p class="font-serif text-lg mt-2"><?= get_db() ? '<span class="text-palm">Connected</span>' : '<span class="text-clay">Using flat-file store</span>' ?></p>
    </div>
  </div>

  <div class="flex items-center justify-between mb-6">
    <h2 class="font-serif text-2xl">Recent posts</h2>
    <a href="/admin/create-post.php" class="bg-ink text-paper px-5 py-2.5 text-sm uppercase tracking-wide hover:bg-clay transition-colors">New post</a>
  </div>

  <div class="divide-y divide-ink/10">
    <?php foreach (array_slice($posts, 0, 5) as $post): ?>
      <div class="flex items-center justify-between py-4">
        <div>
          <p class="font-serif text-lg"><?= e($post['title']) ?></p>
          <p class="font-mono text-xs text-ink/40"><?= e($post['published_at']) ?> &middot; <?= e($post['source']) ?></p>
        </div>
        <a href="/admin/edit-post.php?id=<?= (int)$post['id'] ?>" class="text-sm uppercase tracking-wide border-b border-ink hover:text-clay hover:border-clay">Edit</a>
      </div>
    <?php endforeach; ?>
    <?php if (empty($posts)): ?><p class="text-ink/50 py-6">No posts yet.</p><?php endif; ?>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
