<?php
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/config/database.php';

$page_title       = 'ZABIDA | Zamboanga-Basilan Integrated Development Alliance';
$page_description = 'ZABIDA is a consortium of local NGOs working for peace and development across the Zamboanga Peninsula and Basilan.';
$current_page     = 'index';

require __DIR__ . '/includes/header.php';

$posts = array_slice(get_all_posts(), 0, 3);
?>

<!-- Hero -->
<section id="home" class="min-h-[calc(100vh-80px)] max-w-6xl mx-auto px-6 flex items-center" aria-label="Introduction">
  <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-16 items-center w-full">
    <div>
      <p class="font-mono text-xs uppercase tracking-[0.2em] text-ink/50 mb-6">
        Zamboanga&ndash;Basilan Integrated Development Alliance &middot; Since 2007
      </p>

      <h1 class="font-serif text-[2.75rem] sm:text-6xl md:text-7xl leading-[1.03] mb-8">
        Many<br>
        organizations.<br>
        <span class="italic text-clay">One</span> alliance.
      </h1>

      <p class="text-lg text-ink/70 max-w-md leading-relaxed mb-10">
        ZABIDA brings together member NGOs working side by side on peace, human
        rights, and community development across Zamboanga City, the Zamboanga
        Peninsula, and Basilan &mdash; two provinces, one shared table.
      </p>

      <div class="flex flex-wrap gap-4">
        <a href="#blog" class="bg-ink text-paper px-6 py-3 text-sm uppercase tracking-wide hover:bg-clay transition-colors">
          Browse Recent Posts
        </a>
        <a href="./contact.php" class="border border-ink px-6 py-3 text-sm uppercase tracking-wide hover:bg-ink hover:text-paper transition-colors">
          Get involved
        </a>
      </div>
    </div>

    <div class="hidden md:flex justify-center lg:justify-end">
      <img src="./assets/images/zabida_consortium_logo.png" alt="ZABIDA consortium logo" class="w-full max-w-[320px] h-auto">
    </div>
  </div>
</section>

<main id="main-content">

<!-- About -->
<section id="about" class="max-w-6xl mx-auto px-6 py-20 md:py-28">
  <div class="grid md:grid-cols-[0.4fr_0.6fr] gap-12 md:gap-20">
    <div>
      <p class="font-mono text-xs uppercase tracking-[0.2em] text-ink/50 mb-4">About</p>
      <h2 class="font-serif text-3xl md:text-4xl leading-tight">A shared table, not a single office</h2>
    </div>
    <div class="text-lg text-ink/75 leading-relaxed space-y-5">
      <p>ZABIDA started in 2007 as a consortium of four non-government organizations &mdash; Katilingban sa Kalambuan, Inc. (KKI), Peace Advocates Zamboanga (PAZ), and Reach Out to Others Foundation (ROOF) in Zamboanga City, together with Nagdilaab Foundation Inc. (NFI) based in Basilan &mdash; committed to uplifting the quality of life of disadvantaged sectors across both provinces.</p>
      <p>Since then it has engaged in initiatives spanning peace, human rights, democratic governance, community empowerment, and disaster preparedness and risk reduction, reaching communities that no single organization could reach alone.</p>
      <blockquote class="font-serif italic text-2xl text-ink border-l-2 border-clay pl-6 my-8">"An alliance works because no single organization carries the whole weight."</blockquote>
      <p class="text-base text-ink/60">The alliance is also growing to include Verde Zamboanga and Ganda Gaddung in Basilan, focused on environment-related campaigns and advocacy, plus Campo Vida for sustainable agriculture, and allied partners Youth Solidarity for Peace and Jovenes Alianza de Zamboanga.</p>
    </div>
  </div>
</section>

<!-- Vision / Mission -->
<section class="max-w-6xl mx-auto px-6 pb-20 md:pb-28 grid md:grid-cols-2 gap-10">
  <div class="border-t-2 border-gold pt-6">
    <h3 class="font-serif text-2xl mb-3">Our vision</h3>
    <p class="text-ink/70 leading-relaxed">A consortium of committed and responsive development partners working together towards peaceful, resilient, and empowered communities in the Zamboanga Peninsula and Basilan.</p>
  </div>
  <div class="border-t-2 border-clay pt-6">
    <h3 class="font-serif text-2xl mb-3">Our mission</h3>
    <p class="text-ink/70 leading-relaxed">We commit ourselves to work together to empower the vulnerable sectors towards a dignified life &mdash; with enhanced social and institutional capacities for gender equity, participative and accountable governance, improved security, and environmental sustainability.</p>
  </div>
</section>

<!-- Member NGOs -->
<section id="partners" class="bg-ink text-paper py-20 md:py-28">
  <div class="max-w-6xl mx-auto px-6">
    <p class="font-mono text-xs uppercase tracking-[0.2em] text-paper/50 mb-4">The alliance</p>
    <h2 class="font-serif text-3xl md:text-4xl mb-14">Our member organizations</h2>

    <div class="divide-y divide-paper/15">
      <div class="grid sm:grid-cols-[auto_1fr_auto] gap-4 sm:gap-8 py-7 items-center">
        <div class="w-1.5 h-14 bg-gold" aria-hidden="true"></div>
        <div>
          <h3 class="font-serif text-2xl mb-1">KKI</h3>
          <p class="text-paper/60 text-sm">Katilingban sa Kalambuan, Inc. &mdash; promotes women and children's rights and socialized housing.</p>
        </div>
        <img src="./assets/images/Katilingban.png" alt="" class="h-14 w-14 object-contain hidden sm:block" loading="lazy">
      </div>
      <div class="grid sm:grid-cols-[auto_1fr_auto] gap-4 sm:gap-8 py-7 items-center">
        <div class="w-1.5 h-14 bg-clay" aria-hidden="true"></div>
        <div>
          <h3 class="font-serif text-2xl mb-1">PAZ</h3>
          <p class="text-paper/60 text-sm">Peace Advocates Zamboanga &mdash; a non-profit engaged in the promotion of peace, interreligious dialogue, and advocacy.</p>
        </div>
        <img src="./assets/images/paz_logo.jpg" alt="" class="h-14 w-14 object-contain hidden sm:block" loading="lazy">
      </div>
      <div class="grid sm:grid-cols-[auto_1fr_auto] gap-4 sm:gap-8 py-7 items-center">
        <div class="w-1.5 h-14 bg-palm" aria-hidden="true"></div>
        <div>
          <h3 class="font-serif text-2xl mb-1">ROOF</h3>
          <p class="text-paper/60 text-sm">Reach Out to Others Foundation &mdash; promotes sustainable agriculture and the welfare of marginalized sectors.</p>
        </div>
        <img src="./assets/images/roof_logo.png" alt="" class="h-14 w-14 object-contain hidden sm:block" loading="lazy">
      </div>
      <div class="grid sm:grid-cols-[auto_1fr_auto] gap-4 sm:gap-8 py-7 items-center">
        <div class="w-1.5 h-14 bg-violet" aria-hidden="true"></div>
        <div>
          <h3 class="font-serif text-2xl mb-1">Nagdilaab</h3>
          <p class="text-paper/60 text-sm">Nagdilaab Foundation Inc. &mdash; capability building, conflict transformation, dialogue, cultural contextualization, peacebuilding, and human rights in Basilan.</p>
        </div>
        <img src="./assets/images/nagdilaab_logo.png" alt="" class="h-14 w-14 object-contain hidden sm:block" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- Programs preview -->
<section id="programs" class="max-w-6xl mx-auto px-6 py-20 md:py-28">
  <div class="flex items-end justify-between mb-14 flex-wrap gap-4">
    <div>
      <p class="font-mono text-xs uppercase tracking-[0.2em] text-ink/50 mb-4">What we do</p>
      <h2 class="font-serif text-3xl md:text-4xl">Focus areas</h2>
    </div>
    <a href="./activities.php" class="text-sm uppercase tracking-wide border-b border-ink hover:text-clay hover:border-clay transition-colors">See all programs &rarr;</a>
  </div>

  <div class="grid md:grid-cols-2 gap-x-16 gap-y-12">
    <div class="border-t-2 border-gold pt-5">
      <h3 class="font-serif text-xl mb-2">Peace</h3>
      <p class="text-ink/70 leading-relaxed">Peace education, conflict transformation, interreligious dialogue, and the Annual Mindanao Week of Peace advocacy.</p>
    </div>
    <div class="border-t-2 border-clay pt-5">
      <h3 class="font-serif text-xl mb-2">Socio-economic development</h3>
      <p class="text-ink/70 leading-relaxed">Financing services, enterprise mentoring, Campo Vida agri-learning, financial literacy, and socialized housing.</p>
    </div>
    <div class="border-t-2 border-palm pt-5">
      <h3 class="font-serif text-xl mb-2">Gender &amp; development</h3>
      <p class="text-ink/70 leading-relaxed">GAD planning, budgeting and audit mentoring, plus Anti-VAWC and VAC advocacy.</p>
    </div>
    <div class="border-t-2 border-violet pt-5">
      <h3 class="font-serif text-xl mb-2">Human rights</h3>
      <p class="text-ink/70 leading-relaxed">Policy review, human rights promotion, and IP Sama-Badjao youth rights advocacy with partner ASMAE.</p>
    </div>
  </div>
</section>

<!-- Journal / Blog -->
<section id="blog" class="max-w-6xl mx-auto px-6 py-20 md:py-28 border-t border-ink/10">
  <div class="flex items-end justify-between mb-14 flex-wrap gap-4">
    <div>
      <p class="font-mono text-xs uppercase tracking-[0.2em] text-ink/50 mb-4">Latest updates</p>
      <h2 class="font-serif text-3xl md:text-4xl">Activities</h2>
    </div>
    <!-- ADDED: "View all" link with arrow pointing to activities.php -->
    <a href="/zabida_project/activities-post.php" class="group inline-flex items-center gap-2 font-mono text-xs uppercase tracking-wider text-ink/70 hover:text-clay transition-colors">
      <span>View all activities</span>
      <span class="text-base group-hover:translate-x-1 transition-transform">&rarr;</span>
    </a>
  </div>

  <div id="blog-grid" class="divide-y divide-ink/10">
    <?php if (empty($posts)): ?>
      <p class="text-ink/50 py-10">No journal entries yet &mdash; check back soon, or follow along on <a href="https://www.facebook.com/zabida.org" class="underline hover:text-clay" target="_blank" rel="noopener noreferrer">Facebook</a>.</p>
    <?php else: foreach ($posts as $post): ?>
      <article class="grid grid-cols-1 sm:grid-cols-[80px_1fr_120px] md:grid-cols-[100px_1fr_160px] gap-4 sm:gap-6 items-start py-8">
        <p class="font-mono text-sm text-ink/40"><?= e(format_post_date($post['published_at'])) ?></p>
        <div>
         <h3 class="font-serif text-2xl mb-2 hover:text-clay transition-colors">
          <a href="/zabida_project/post.php?id=<?= (int)$post['id'] ?>" class="js-post-link" data-post-id="<?= (int)$post['id'] ?>"><?= e($post['title']) ?></a>
        </h3>
          <p class="text-ink/60 leading-relaxed"><?= e($post['excerpt']) ?></p>
        </div>
        <div class="aspect-square w-full rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center border border-ink/10">
          <img src="/zabida_project/<?= e($post['image']) ?>" alt="" class="w-full h-full object-contain p-1">
        </div>
      </article>
    <?php endforeach; endif; ?>
  </div>
</section>



</main>
<!-- Post Modal -->
<div id="post-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
  <div id="post-modal-backdrop" class="absolute inset-0 bg-ink/60 backdrop-blur-sm"></div>
  <div class="relative h-full flex items-start sm:items-center justify-center p-4 sm:p-8 overflow-y-auto">
    <div class="relative bg-paper w-full max-w-3xl rounded-lg shadow-2xl my-8 sm:my-0">
      <button id="post-modal-close" type="button" class="absolute top-4 right-4 z-10 w-9 h-9 flex items-center justify-center rounded-full bg-ink/5 hover:bg-ink/10 text-ink transition-colors" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      <div id="post-modal-content" class="px-6 py-10 sm:px-10 sm:py-12">
        <div class="animate-pulse text-ink/40 font-mono text-sm">Loading&hellip;</div>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  var modal      = document.getElementById('post-modal');
  var backdrop   = document.getElementById('post-modal-backdrop');
  var closeBtn   = document.getElementById('post-modal-close');
  var contentBox = document.getElementById('post-modal-content');
  var lastFocused;

  function openModal(id) {
    lastFocused = document.activeElement;
    contentBox.innerHTML = '<div class="animate-pulse text-ink/40 font-mono text-sm">Loading&hellip;</div>';
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
    history.pushState({ postModal: id }, '', './post.php?id=' + id);

    fetch('./post-fragment.php?id=' + encodeURIComponent(id))
      .then(function (res) { return res.text(); })
      .then(function (html) { contentBox.innerHTML = html; })
      .catch(function () {
        contentBox.innerHTML = '<p class="text-ink/70">Sorry, something went wrong loading this post.</p>';
      });
  }

  function closeModal(skipHistory) {
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
    if (!skipHistory) history.pushState({}, '', './index.php#blog');
    if (lastFocused) lastFocused.focus();
  }

  document.addEventListener('click', function (e) {
    var link = e.target.closest('.js-post-link');
    if (link) {
      e.preventDefault();
      openModal(link.dataset.postId);
    }
  });

  closeBtn.addEventListener('click', function () { closeModal(); });
  backdrop.addEventListener('click', function () { closeModal(); });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
  });

  window.addEventListener('popstate', function () {
    if (!modal.classList.contains('hidden')) closeModal(true);
  });
})();
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
