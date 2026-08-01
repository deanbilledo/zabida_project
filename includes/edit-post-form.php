<?php
/** Expects $post, $errors (array) to be set by whatever includes this file. */
?>
<?php if (!empty($errors)): ?>
  <div class="text-clay text-sm font-medium border border-clay/30 bg-clay/5 px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<form method="POST" class="js-edit-form space-y-7" action="edit-post.php" data-post-id="<?= (int)$post['id'] ?>">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
  <div>
    <label for="title" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Title</label>
    <input type="text" id="title" name="title" required value="<?= e($post['title']) ?>" class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0">
  </div>
  <div>
    <label for="excerpt" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Excerpt</label>
    <textarea id="excerpt" name="excerpt" rows="2" required class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 resize-none"><?= e($post['excerpt']) ?></textarea>
  </div>
  <div>
    <label for="body" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Full text</label>
    <textarea id="body" name="body" rows="6" class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 resize-none"><?= e($post['body']) ?></textarea>
  </div>
  <div>
    <label for="published_at" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Publish date</label>
    <input type="date" id="published_at" name="published_at" value="<?= e($post['published_at']) ?>" class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0">
  </div>
  <button type="submit" class="js-edit-submit bg-ink text-paper px-8 py-3 text-sm uppercase tracking-wide hover:bg-clay transition-colors">Save changes</button>
</form>