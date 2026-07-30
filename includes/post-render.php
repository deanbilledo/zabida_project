<?php
/** Expects $post to be set by whatever includes this file. */
?>
<p class="font-mono text-sm text-ink/40 mb-3"><?= e(format_post_date($post['published_at'])) ?></p>
<h1 class="font-serif text-3xl md:text-5xl mb-8 leading-tight"><?= e($post['title']) ?></h1>
<?php if (!empty($post['image'])): ?>
  <div class="aspect-video w-full rounded-lg bg-gray-100 overflow-hidden border border-ink/10 mb-10">
    <img src="/zabida_project/<?= e($post['image']) ?>" alt="<?= e($post['title'] ?? '') ?>" class="w-full h-full object-cover">
  </div>
<?php endif; ?>
<div class="prose prose-lg max-w-none text-ink/80 leading-relaxed whitespace-pre-line"><?= e($post['body']) ?></div>