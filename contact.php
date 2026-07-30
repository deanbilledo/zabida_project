<?php
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/config/auth.php'; // for csrf_token()/csrf_check()

$page_title       = 'Contact | ZABIDA';
$page_description = 'Get in touch with ZABIDA — partner organizations, volunteers, and donors are welcome.';
$current_page     = 'contact';

$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Your session expired — please try again.';
    }
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '')    $errors[] = 'Full name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
    if ($message === '') $errors[] = 'Message is required.';

    if (empty($errors)) {
        // TODO: wire up mail() / PHPMailer to zabidamail.ph@gmail.com once
        // the server's outbound mail is configured. For now the message
        // is logged so nothing is lost during development.
        error_log(sprintf('[ZABIDA contact] %s <%s>: %s', $name, $email, $message));
        $success = true;
    }
}

require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
<section class="max-w-6xl mx-auto px-6 py-20 md:py-28">
  <div class="grid lg:grid-cols-[0.4fr_0.6fr] gap-12 md:gap-20">
    <div>
      <p class="font-mono text-xs uppercase tracking-[0.2em] text-ink/50 mb-4">Get in touch</p>
      <h1 class="font-serif text-3xl md:text-4xl mb-10">Contact us</h1>

      <dl class="space-y-6 text-ink/75">
        <div>
          <dt class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-1">Address</dt>
          <dd>Macrohon Compound, Suterville, San Jose Gusu, Zamboanga City, Philippines</dd>
        </div>
        <div>
          <dt class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-1">Email</dt>
          <dd><a href="mailto:zabidamail.ph@gmail.com" class="hover:text-clay">zabidamail.ph@gmail.com</a></dd>
        </div>
        <div>
          <dt class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-1">Phone</dt>
          <dd><a href="tel:0629902410" class="hover:text-clay">0629902410</a></dd>
        </div>
        <div>
          <dt class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-1">Facebook</dt>
          <dd><a href="https://www.facebook.com/zabida.org" target="_blank" rel="noopener noreferrer" class="hover:text-clay">facebook.com/zabida.org</a></dd>
        </div>
        <div>
          <dt class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-1">YouTube</dt>
          <dd><a href="https://www.youtube.com/zabidaorg" target="_blank" rel="noopener noreferrer" class="hover:text-clay">youtube.com/zabidaorg</a></dd>
        </div>
        <div>
          <dt class="font-mono text-xs uppercase tracking-wide text-ink/40 mb-1">Website</dt>
          <dd><a href="https://www.zabida.org/ph" target="_blank" rel="noopener noreferrer" class="hover:text-clay">zabida.org/ph</a></dd>
        </div>
      </dl>
    </div>

    <div>
      <?php if ($success): ?>
        <div class="text-palm text-sm font-medium border border-palm/30 bg-palm/5 px-5 py-4 mb-6">Message sent. We'll get back to you soon.</div>
      <?php endif; ?>
      <?php if (!empty($errors)): ?>
        <div class="text-clay text-sm font-medium border border-clay/30 bg-clay/5 px-5 py-4 mb-6">
          <ul class="list-disc list-inside space-y-1">
            <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form id="contactForm" class="space-y-7" method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div>
          <label for="name" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Full name</label>
          <input type="text" id="name" name="name" required value="<?= e($_POST['name'] ?? '') ?>" class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 transition-colors">
        </div>
        <div>
          <label for="email" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Email address</label>
          <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 transition-colors">
        </div>
        <div>
          <label for="message" class="block font-mono text-xs uppercase tracking-wide text-ink/50 mb-2">Message</label>
          <textarea id="message" name="message" rows="4" required class="w-full bg-transparent border-0 border-b border-ink/25 py-2 focus:border-clay focus:ring-0 transition-colors resize-none"><?= e($_POST['message'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="bg-ink text-paper px-8 py-3 text-sm uppercase tracking-wide hover:bg-clay transition-colors">Send message</button>
      </form>
    </div>
  </div>
</section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
