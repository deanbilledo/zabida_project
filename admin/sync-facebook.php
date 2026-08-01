<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../config/facebook.php';
require __DIR__ . '/../includes/functions.php';
require_login();

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['csrf_token'] ?? '')) {
    $action = $_POST['action'] ?? 'sync';

    if ($action === 'save_settings') {
        fb_settings_store_write([
            'page_id'    => trim($_POST['page_id'] ?? ''),
            'app_id'     => trim($_POST['app_id'] ?? ''),
            'app_secret' => trim($_POST['app_secret'] ?? ''),
        ]);
        $result = ['ok' => true, 'message' => 'Facebook connection settings saved.'];

    } elseif ($action === 'exchange_token') {
        $shortLived = trim($_POST['short_lived_token'] ?? '');
        if ($shortLived === '') {
            $result = ['ok' => false, 'message' => 'Please paste a token to exchange.'];
        } else {
            require __DIR__ . '/../api/facebook-sync.php';
            $exchange = facebook_exchange_long_lived_token($shortLived);
            $result = $exchange['ok']
                ? ['ok' => true, 'message' => 'Token exchanged and stored successfully. You can now sync.']
                : ['ok' => false, 'message' => 'Exchange failed: ' . $exchange['error']];
        }

    } else {
        require __DIR__ . '/../api/facebook-sync.php';
        $result = run_facebook_sync();
    }
}

$needsReauth = facebook_token_needs_reauth();

$page_title   = 'Facebook sync | ZABIDA admin';
$current_page = 'admin';
$admin_page   = 'sync-facebook';
require __DIR__ . '/../includes/header.php';
?>
<main id="main-content" class="max-w-2xl mx-auto px-6 py-16">
  <?php require __DIR__ . '/_admin_nav.php'; ?>

  <h1 class="font-serif text-3xl mb-4">Facebook sync</h1>
  <p class="text-ink/70 leading-relaxed mb-10">Pulls recent posts from <a href="https://www.facebook.com/profile.php?id=61568046598637" target="_blank" rel="noopener noreferrer" class="underline hover:text-clay">facebook.com/zabida.org</a> into the Journal. Runs automatically via the scheduler, or trigger it manually below.</p>

  <?php if (!facebook_sync_ready() && !$needsReauth): ?>
    <div class="text-clay text-sm font-medium border border-clay/30 bg-clay/5 px-5 py-4 mb-8">
      Facebook connection isn't fully configured yet — fill in the settings below.
    </div>
  <?php endif; ?>

  <?php if ($needsReauth): ?>
    <div class="text-clay text-sm font-medium border border-clay/30 bg-clay/5 px-5 py-4 mb-8">
      Your Facebook token expired or was revoked. Paste a fresh short-lived token below to re-authenticate.
    </div>
  <?php endif; ?>

  <?php if ($result): ?>
    <div class="text-sm font-medium border px-5 py-4 mb-8 <?= $result['ok'] ? 'text-palm border-palm/30 bg-palm/5' : 'text-clay border-clay/30 bg-clay/5' ?>">
      <?= e($result['message']) ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="mb-14">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <button type="submit" <?= facebook_sync_ready() ? '' : 'disabled' ?>
      class="bg-ink text-paper px-8 py-3 text-sm uppercase tracking-wide hover:bg-clay transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
      Sync now
    </button>
  </form>

  <div class="border-t border-ink/10 pt-8 mb-10">
    <h2 class="font-serif text-2xl mb-2">Connection settings</h2>
    <p class="text-ink/60 text-sm mb-6">Your Meta App's Page ID, App ID, and App Secret — found under App Dashboard → Settings → Basic, and via Graph API Explorer for the Page ID.</p>
    <form method="POST" class="space-y-6">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="save_settings">

      <div>
        <label for="page_id" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Facebook Page ID</label>
        <input type="text" id="page_id" name="page_id" value="<?= e(facebook_page_id()) ?>"
          placeholder="e.g. 402629069611373"
          class="w-full bg-transparent border-0 border-b border-ink/25 py-2 font-mono text-sm focus:border-clay focus:ring-0">
      </div>

      <div>
        <label for="app_id" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Meta App ID</label>
        <input type="text" id="app_id" name="app_id" value="<?= e(facebook_app_id()) ?>"
          placeholder="e.g. 1234567890123456"
          class="w-full bg-transparent border-0 border-b border-ink/25 py-2 font-mono text-sm focus:border-clay focus:ring-0">
      </div>

      <div>
        <label for="app_secret" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Meta App Secret</label>
        <input type="password" id="app_secret" name="app_secret" value="<?= e(facebook_app_secret()) ?>"
          placeholder="Kept private, never shown elsewhere"
          class="w-full bg-transparent border-0 border-b border-ink/25 py-2 font-mono text-sm focus:border-clay focus:ring-0">
        <p class="text-ink/40 text-xs mt-2">Never share this — it can be used to act as your Meta App.</p>
      </div>

      <button type="submit" class="bg-ink text-paper px-6 py-2.5 text-sm uppercase tracking-wide hover:bg-clay transition-colors">
        Save connection settings
      </button>
    </form>
  </div>

  <details class="border-t border-ink/10 pt-6" <?= $needsReauth ? 'open' : '' ?>>
    <summary class="font-mono text-xs uppercase tracking-wide text-ink/50 cursor-pointer mb-4">
      Re-authenticate / exchange a fresh token
    </summary>
    <p class="text-ink/60 text-sm mb-4">Paste a new short-lived Page Access Token from Graph API Explorer — it'll be exchanged for a long-lived one and stored automatically.</p>
    <form method="POST" class="flex flex-col gap-4">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="exchange_token">
      <textarea name="short_lived_token" rows="3" placeholder="Paste short-lived token here"
        class="w-full bg-transparent border border-ink/25 px-3 py-2 text-sm font-mono focus:border-clay focus:ring-0"></textarea>
      <button type="submit" class="self-start border border-ink px-6 py-2.5 text-sm uppercase tracking-wide hover:bg-ink hover:text-paper transition-colors">
        Exchange &amp; store token
      </button>
    </form>
  </details>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>