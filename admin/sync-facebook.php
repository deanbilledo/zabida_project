<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../config/facebook.php';
require __DIR__ . '/../includes/functions.php';
require_login();

$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['csrf_token'] ?? '')) {
    require __DIR__ . '/../api/facebook-sync.php'; // defines run_facebook_sync()
    $result = run_facebook_sync();
}

$page_title   = 'Facebook sync | ZABIDA admin';
$current_page = 'admin';
$admin_page   = 'sync-facebook';
require __DIR__ . '/../includes/header.php';
?>
<main id="main-content" class="max-w-2xl mx-auto px-6 py-16">
  <?php require __DIR__ . '/_admin_nav.php'; ?>

  <h1 class="font-serif text-3xl mb-4">Facebook sync</h1>
  <p class="text-ink/70 leading-relaxed mb-10">Pulls recent posts from <a href="https://www.facebook.com/zabidadotorg" target="_blank" rel="noopener noreferrer" class="underline hover:text-clay">facebook.com/zabida.org</a> into the Journal. Runs automatically via the scheduler, or trigger it manually below.</p>

  <?php if (!facebook_sync_ready()): ?>
    <div class="text-clay text-sm font-medium border border-clay/30 bg-clay/5 px-5 py-4 mb-8">
      No Facebook Page Access Token is configured yet. Set <code>ZABIDA_FB_PAGE_TOKEN</code> in your environment (see <code>config/facebook.php</code>) before syncing.
    </div>
  <?php endif; ?>

  <?php if ($result): ?>
    <div class="text-sm font-medium border px-5 py-4 mb-8 <?= $result['ok'] ? 'text-palm border-palm/30 bg-palm/5' : 'text-clay border-clay/30 bg-clay/5' ?>">
      <?= e($result['message']) ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <button type="submit" class="bg-ink text-paper px-8 py-3 text-sm uppercase tracking-wide hover:bg-clay transition-colors">Sync now</button>
  </form>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
