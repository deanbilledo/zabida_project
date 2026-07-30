<?php
/**
 * ZABIDA — Page head + opening body
 * Expects $page_title and optional $page_description to be set by the
 * including page before this file is required.
 */
$page_title       = $page_title ?? 'ZABIDA | Zamboanga-Basilan Integrated Development Alliance';
$page_description = $page_description ?? 'ZABIDA is a consortium of local NGOs working for peace and development across the Zamboanga Peninsula and Basilan.';
?>
<!DOCTYPE html>
<html lang="en-PH">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= e($page_title) ?></title>
<meta name="description" content="<?= e($page_description) ?>">
<meta name="theme-color" content="#17303D">
<link rel="icon" href="/assets/images/zabida_logo.png" type="image/png">

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Work+Sans:wght@400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">

<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        serif: ['Fraunces', 'serif'],
        sans: ['Work Sans', 'sans-serif'],
        mono: ['Space Mono', 'monospace']
      },
      colors: {
        ink: '#17303D',
        paper: '#EEF2EE',
        clay: '#B14A2E',
        gold: '#D9A72B',
        palm: '#3E6B4F',
        violet: '#4A3B7A'
      }
    }
  }
}
</script>
</head>
<body class="font-sans bg-paper text-ink">

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-ink text-paper px-4 py-2 z-50">Skip to main content</a>

<!-- Stripe rule: the recurring signature motif, echoing a vinta sail's banded colors -->
<div class="flex h-1.5 w-full" aria-hidden="true">
  <div class="flex-1 bg-gold"></div>
  <div class="flex-1 bg-clay"></div>
  <div class="flex-1 bg-ink"></div>
  <div class="flex-1 bg-palm"></div>
  <div class="flex-1 bg-violet"></div>
</div>

<header role="banner">
<?php require __DIR__ . '/navbar.php'; ?>
</header>
