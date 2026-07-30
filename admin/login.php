<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        $error = 'Your session expired — please try again.';
    } elseif (attempt_login($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Incorrect username or password.';
    }
}

$page_title   = 'Admin login | ZABIDA';
$current_page = 'admin';
require __DIR__ . '/../includes/header.php';
?>
<main id="main-content" class="max-w-md mx-auto px-6 py-24">
  <p class="font-mono text-xs uppercase tracking-[0.2em] text-ink/50 mb-4">ZABIDA admin</p>
  <h1 class="font-serif text-3xl mb-10">Sign in</h1>

  <?php if ($error): ?>
    <div class="text-clay text-sm font-medium border border-clay/30 bg-clay/5 px-5 py-4 mb-6"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="POST" class="space-y-7">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <div>
      <label for="username" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Username</label>
      <input type="text" id="username" name="username" required autofocus class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 transition-colors">
    </div>
    <div>
      <label for="password" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Password</label>
      <input type="password" id="password" name="password" required class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 transition-colors">
    </div>
    <button type="submit" class="w-full bg-ink text-paper px-8 py-3 text-sm uppercase tracking-wide hover:bg-clay transition-colors">Sign in</button>
  </form>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
