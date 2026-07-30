<?php
/**
 * ZABIDA — Nav bar
 * Set $current_page (e.g. 'index', 'activities', 'contact') in the
 * including page to highlight the matching link.
 */
$current_page = $current_page ?? 'index';

function nav_class(string $page, string $current): string
{
    $base = 'nav-link hover:text-clay transition-colors';
    return $page === $current ? $base . ' active' : $base;
}
?>
<nav id="navbar" class="sticky top-0 z-50 bg-paper/95 backdrop-blur-sm border-b border-ink/10" role="navigation" aria-label="Main navigation">
  <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
    <a href="/index.php" class="flex items-center gap-3" aria-label="ZABIDA home">
      <img src="/assets/images/zabida_logo.png" alt="ZABIDA logo" width="48" height="48" class="h-9 w-auto object-contain" onerror="this.style.display='none'">
      <span class="font-serif font-medium text-lg tracking-tight">ZABIDA</span>
    </a>

    <ul class="hidden lg:flex items-center gap-8 text-sm tracking-wide uppercase">
      <li><a href="/index.php#about" class="<?= nav_class('index', $current_page) ?>">About</a></li>
      <li><a href="/index.php#partners" class="<?= nav_class('index', $current_page) ?>">Member NGOs</a></li>
      <li><a href="/activities.php" class="<?= nav_class('activities', $current_page) ?>">Programs</a></li>
      <li><a href="/index.php#blog" class="<?= nav_class('index', $current_page) ?>">Journal</a></li>
      <li><a href="/contact.php" class="border border-ink px-4 py-1.5 hover:bg-ink hover:text-paper transition-colors <?= $current_page === 'contact' ? 'bg-ink text-paper' : '' ?>">Contact</a></li>
    </ul>

    <button class="lg:hidden mobile-menu p-2" aria-label="Toggle menu" aria-expanded="false">
      <div class="w-6 h-5 flex flex-col justify-between">
        <span class="w-full h-0.5 bg-ink"></span>
        <span class="w-full h-0.5 bg-ink"></span>
        <span class="w-full h-0.5 bg-ink"></span>
      </div>
    </button>
  </div>

  <div class="lg:hidden nav-links hidden border-t border-ink/10 px-6 py-4">
    <div class="flex flex-col gap-1 text-sm uppercase tracking-wide">
      <a href="/index.php#about" class="py-2.5">About</a>
      <a href="/index.php#partners" class="py-2.5">Member NGOs</a>
      <a href="/activities.php" class="py-2.5">Programs</a>
      <a href="/index.php#blog" class="py-2.5">Journal</a>
      <a href="/contact.php" class="py-2.5 font-semibold">Contact</a>
    </div>
  </div>
</nav>
