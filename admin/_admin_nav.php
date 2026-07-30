<?php
// Internal partial — included by every admin/*.php page. Not linked directly.
$admin_page = $admin_page ?? basename($_SERVER['SCRIPT_NAME'], '.php');
?>
<div class="flex flex-wrap items-center justify-between gap-4 border-b border-ink/10 pb-6 mb-10">
  <div class="flex gap-6 text-sm uppercase tracking-wide">
    <a href="/admin/dashboard.php" class="<?= $admin_page === 'dashboard' ? 'text-clay' : 'text-ink/60 hover:text-clay' ?>">Dashboard</a>
    <a href="/admin/posts.php" class="<?= $admin_page === 'posts' ? 'text-clay' : 'text-ink/60 hover:text-clay' ?>">Posts</a>
    <a href="/admin/sync-facebook.php" class="<?= $admin_page === 'sync-facebook' ? 'text-clay' : 'text-ink/60 hover:text-clay' ?>">Facebook sync</a>
  </div>
  <a href="/admin/logout.php" class="text-sm uppercase tracking-wide text-ink/40 hover:text-clay">Sign out</a>
</div>
