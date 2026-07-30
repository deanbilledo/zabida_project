document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('current-year').textContent = new Date().getFullYear();

  // Mobile menu toggle
  const mobileMenu = document.querySelector('.mobile-menu');
  const navLinks = document.querySelector('.nav-links');

  if (mobileMenu && navLinks) {
    mobileMenu.addEventListener('click', () => {
      const isActive = mobileMenu.classList.toggle('active');
      navLinks.classList.toggle('hidden');
      mobileMenu.setAttribute('aria-expanded', isActive ? 'true' : 'false');
    });

    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        navLinks.classList.add('hidden');
        mobileMenu.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // Smooth scroll for in-page links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      const target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Navbar shadow on scroll
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (!navbar) return;
    navbar.classList.toggle('scrolled', window.scrollY > 40);
  }, { passive: true });

  // Active section highlighting
  const sections = document.querySelectorAll('section[id]');
  const navLinkEls = document.querySelectorAll('.nav-link[href^="#"]');

  function updateActiveNav() {
    let current = 'home';
    const scrollPos = window.scrollY + 150;
    sections.forEach(section => {
      if (scrollPos >= section.offsetTop && scrollPos < section.offsetTop + section.offsetHeight) {
        current = section.getAttribute('id');
      }
    });
    navLinkEls.forEach(link => {
      link.classList.toggle('active', link.getAttribute('href') === `#${current}`);
    });
  }
  window.addEventListener('scroll', updateActiveNav, { passive: true });
  updateActiveNav();

  // Contact form: client-side validation + CSRF-ready submission
  const form = document.getElementById('contactForm');
  const formSuccess = document.getElementById('formSuccess');
  const formError = document.getElementById('formError');
  const submitBtn = document.getElementById('submitBtn');

  function showFieldError(field, message) {
    const msgEl = field.parentElement.querySelector('.error-message');
    field.classList.add('border-red-400');
    if (msgEl) {
      msgEl.textContent = message;
      msgEl.classList.remove('hidden');
    }
  }

  function clearFieldError(field) {
    const msgEl = field.parentElement.querySelector('.error-message');
    field.classList.remove('border-red-400');
    if (msgEl) msgEl.classList.add('hidden');
  }

  function validate(field) {
    const value = field.value.trim();
    clearFieldError(field);

    if (field.name === 'name' && value.length < 2) {
      showFieldError(field, 'Enter your full name.');
      return false;
    }
    if (field.name === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
      showFieldError(field, 'Enter a valid email address.');
      return false;
    }
    if (field.name === 'message' && value.length < 10) {
      showFieldError(field, 'Message should be at least 10 characters.');
      return false;
    }
    return true;
  }

  if (form) {
    const fields = form.querySelectorAll('input[required], textarea[required]');
    fields.forEach(field => {
      field.addEventListener('blur', () => validate(field));
      field.addEventListener('input', () => clearFieldError(field));
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      let valid = true;
      fields.forEach(field => { if (!validate(field)) valid = false; });
      if (!valid) return;

      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending...';
      formSuccess.classList.add('hidden');
      formError.classList.add('hidden');

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) throw new Error('Request failed');
        formSuccess.classList.remove('hidden');
        form.reset();
      } catch (err) {
        formError.classList.remove('hidden');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send message';
      }
    });
  }
});