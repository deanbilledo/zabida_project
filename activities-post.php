<?php
require __DIR__ . '/includes/functions.php';

$posts = get_all_posts();

$page_title   = 'Activities & Programs | ZABIDA';
$current_page = 'activities';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content" class="max-w-6xl mx-auto px-6 py-16 md:py-24">
  <!-- Section Title -->
  <div class="mb-14 border-b border-ink/10 pb-8">
    <p class="font-mono text-xs uppercase tracking-[0.2em] text-ink/50 mb-3">Our Work & News</p>
    <h1 class="font-serif text-4xl md:text-5xl">All Activities</h1>
  </div>

  <!-- Activities Listing -->
  <div class="divide-y divide-ink/10">
    <?php if (empty($posts)): ?>
      <p class="text-ink/50 py-12 text-center">No activities or journal entries published yet.</p>
    <?php else: foreach ($posts as $post): ?>
      <article class="grid grid-cols-1 md:grid-cols-[120px_1fr_200px] gap-6 md:gap-8 items-start py-10 group">
        <!-- Date -->
        <p class="font-mono text-sm text-ink/40"><?= e(format_post_date($post['published_at'])) ?></p>
        
        <!-- Content -->
        <div>
          <h2 class="font-serif text-2xl md:text-3xl mb-3 group-hover:text-clay transition-colors">
            <a href="/zabida_project/post.php?id=<?= (int)$post['id'] ?>">
              <?= e($post['title']) ?>
            </a>
          </h2>
          <p class="text-ink/70 leading-relaxed mb-4"><?= e($post['excerpt']) ?></p>
          <a href="/zabida_project/post.php?id=<?= (int)$post['id'] ?>" class="inline-flex items-center gap-2 font-mono text-xs uppercase tracking-wider text-clay hover:underline">
            <span>Read full story</span>
            <span>&rarr;</span>
          </a>
        </div>

        <!-- Thumbnail Image -->
        <div class="aspect-square w-full rounded-lg bg-gray-100 overflow-hidden border border-ink/10">
          <img src="/zabida_project/<?= e($post['image']) ?>" alt="<?= e($post['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        </div>
      </article>
    <?php endforeach; endif; ?>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>