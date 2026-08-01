<!-- Shared modal (view / edit / delete-confirm) -->
<div id="admin-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
  <div id="admin-modal-backdrop" class="absolute inset-0 bg-ink/60 backdrop-blur-sm"></div>
  <div class="relative h-full flex items-start sm:items-center justify-center p-4 sm:p-8 overflow-y-auto">
    <div id="admin-modal-card" class="relative bg-paper w-full max-w-2xl rounded-lg shadow-2xl my-8 sm:my-0">
      <button id="admin-modal-close" type="button" class="absolute top-4 right-4 z-10 w-9 h-9 flex items-center justify-center rounded-full bg-ink/5 hover:bg-ink/10 text-ink transition-colors" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      <div id="admin-modal-content" class="px-6 py-10 sm:px-10 sm:py-12">
        <div class="animate-pulse text-ink/40 font-mono text-sm">Loading&hellip;</div>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  var modal      = document.getElementById('admin-modal');
  var backdrop   = document.getElementById('admin-modal-backdrop');
  var closeBtn   = document.getElementById('admin-modal-close');
  var contentBox = document.getElementById('admin-modal-content');
  var lastFocused;

  // Directory of the current page (e.g. ".../zabida_project/admin/"),
  // so requests resolve correctly no matter which admin page includes this partial.
  var adminBase = window.location.pathname.replace(/[^\/]*$/, '');

  function showModal() {
    lastFocused = document.activeElement;
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
  }

  function closeModal() {
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
    contentBox.innerHTML = '';
    if (lastFocused) lastFocused.focus();
  }

  function loading() {
    contentBox.innerHTML = '<div class="animate-pulse text-ink/40 font-mono text-sm">Loading&hellip;</div>';
  }

  function fetchOpts(extra) {
    return Object.assign({ headers: { 'X-Requested-With': 'fetch' } }, extra || {});
  }

  // ---- View ----
  function openView(id) {
    loading();
    showModal();
    fetch(adminBase + '../post-fragment.php?id=' + encodeURIComponent(id), fetchOpts())
      .then(function (res) { return res.text(); })
      .then(function (html) { contentBox.innerHTML = html; })
      .catch(function () {
        contentBox.innerHTML = '<p class="text-ink/70">Sorry, something went wrong loading this post.</p>';
      });
  }

  // ---- Edit ----
  function openEdit(id) {
    loading();
    showModal();
    fetch(adminBase + 'edit-post.php?id=' + encodeURIComponent(id), fetchOpts())
      .then(function (res) {
        var ct = res.headers.get('Content-Type') || '';
        if (ct.indexOf('application/json') !== -1) {
          return res.json().then(function (data) { throw new Error(data.message || 'Not found'); });
        }
        return res.text();
      })
      .then(function (html) {
        contentBox.innerHTML = '<h2 class="font-serif text-2xl mb-8">Edit post</h2>' + html;
        bindEditForm();
      })
      .catch(function (err) {
        contentBox.innerHTML = '<p class="text-clay">' + (err.message || 'Could not load this post for editing.') + '</p>';
      });
  }

  function bindEditForm() {
    var form = contentBox.querySelector('.js-edit-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var submitBtn = form.querySelector('.js-edit-submit');
      var originalLabel = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Saving…';

      fetch(adminBase + 'edit-post.php', fetchOpts({
        method: 'POST',
        body: new URLSearchParams(new FormData(form))
      }))
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data.ok) {
            closeModal();
            location.reload();
          } else {
            submitBtn.disabled = false;
            submitBtn.textContent = originalLabel;
            var msg = (data.errors || [data.message]).join(' ');
            var existingErr = contentBox.querySelector('.js-edit-error');
            if (existingErr) existingErr.remove();
            var errBox = document.createElement('div');
            errBox.className = 'js-edit-error text-clay text-sm font-medium border border-clay/30 bg-clay/5 px-5 py-4 mb-6';
            errBox.textContent = msg;
            form.parentNode.insertBefore(errBox, form);
          }
        })
        .catch(function () {
          submitBtn.disabled = false;
          submitBtn.textContent = originalLabel;
        });
    });
  }

  // ---- Delete confirmation ----
  function openDeleteConfirm(id, title, csrfToken, onDeleted) {
    showModal();
    contentBox.innerHTML =
      '<h2 class="font-serif text-2xl mb-3">Delete this post?</h2>' +
      '<p class="text-ink/70 leading-relaxed mb-8">' +
        '&ldquo;<span class="font-medium"></span>&rdquo; will be permanently removed. This cannot be undone.' +
      '</p>' +
      '<div class="flex gap-4">' +
        '<button type="button" id="admin-modal-cancel" class="px-5 py-2.5 text-sm uppercase tracking-wide border border-ink/20 hover:bg-ink/5 transition-colors">Cancel</button>' +
        '<button type="button" id="admin-modal-confirm-delete" class="px-5 py-2.5 text-sm uppercase tracking-wide bg-clay text-paper hover:bg-clay/90 transition-colors">Delete post</button>' +
      '</div>';

    contentBox.querySelector('span').textContent = title;
    contentBox.querySelector('#admin-modal-cancel').addEventListener('click', closeModal);

    contentBox.querySelector('#admin-modal-confirm-delete').addEventListener('click', function (e) {
      var btn = e.currentTarget;
      btn.disabled = true;
      btn.textContent = 'Deleting…';

      var body = new URLSearchParams();
      body.set('csrf_token', csrfToken);
      body.set('id', id);

      fetch(adminBase + 'delete-post.php', fetchOpts({
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'fetch' },
        body: body.toString()
      }))
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (!data.ok) throw new Error(data.message || 'Delete failed');
          closeModal();
          if (typeof onDeleted === 'function') onDeleted(id);
        })
        .catch(function (err) {
          contentBox.innerHTML = '<p class="text-clay">' + (err.message || 'Something went wrong deleting this post.') + '</p>';
        });
    });
  }

  document.addEventListener('click', function (e) {
    var viewLink = e.target.closest('.js-view-link');
    if (viewLink) { e.preventDefault(); openView(viewLink.dataset.postId); return; }

    var editLink = e.target.closest('.js-edit-link');
    if (editLink) { e.preventDefault(); openEdit(editLink.dataset.postId); return; }

    var deleteBtn = e.target.closest('.js-delete-btn');
    if (deleteBtn) {
      e.preventDefault();
      openDeleteConfirm(deleteBtn.dataset.postId, deleteBtn.dataset.postTitle, deleteBtn.dataset.csrf, function (id) {
        var row = document.querySelector('[data-post-id="' + id + '"]');
        if (row) {
          var container = row.closest('.divide-y > div');
          if (container) container.remove();
        }
      });
      return;
    }
  });

  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
  });
})();
</script>