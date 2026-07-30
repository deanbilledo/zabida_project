// Modern JavaScript for the redesigned website
document.addEventListener('DOMContentLoaded', function() {
    // Dark mode functionality
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const body = document.body;

    // Check for saved dark mode preference or default to light mode
    const currentMode = localStorage.getItem('darkMode') || 'light';
    if (currentMode === 'dark') {
        body.classList.add('dark');
        darkModeIcon.className = 'fas fa-sun';
    }

    darkModeToggle?.addEventListener('click', () => {
        body.classList.toggle('dark');
        const isDark = body.classList.contains('dark');
        
        darkModeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
        
        // Add a little animation to the toggle
        darkModeToggle.style.transform = 'scale(0.8)';
        setTimeout(() => {
            darkModeToggle.style.transform = 'scale(1)';
        }, 100);
    });

    // Counter animation for stats
    function animateCounters() {
        const counters = document.querySelectorAll('.counter[data-target]');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            
            // Skip if target is not a valid number
            if (isNaN(target) || target <= 0) {
                console.warn('Invalid data-target for counter:', counter);
                return;
            }
            
            // Store original content as fallback
            const originalContent = counter.textContent;
            const increment = target / 100;
            let current = 0;
            let hasStarted = false;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    const displayValue = Math.floor(current);
                    const suffix = target === 100 ? '' : '+';
                    counter.textContent = displayValue + suffix;
                    setTimeout(updateCounter, 20);
                } else {
                    const suffix = target === 100 ? '%' : '+';
                    counter.textContent = target + suffix;
                }
            };
            
            // Start animation when element is in view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !hasStarted) {
                        hasStarted = true;
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(counter);
        });
    }

    // Initialize counter animations
    animateCounters();

    // Clean up any corrupted text content (NaN issues)
    function cleanupCorruptedText() {
        const allElements = document.querySelectorAll('*');
        allElements.forEach(element => {
            if (element.textContent && element.textContent.includes('NaN')) {
                console.warn('Found corrupted text content, cleaning up:', element);
                // If it's a counter element, reset it
                if (element.classList.contains('counter')) {
                    const target = element.getAttribute('data-target');
                    if (target) {
                        element.textContent = '0';
                    }
                } else {
                    // For other elements, try to restore original content
                    const originalText = element.getAttribute('data-original-text');
                    if (originalText) {
                        element.textContent = originalText;
                    } else {
                        // Remove NaN content
                        element.textContent = element.textContent.replace(/NaN/g, '');
                    }
                }
            }
        });
    }

    // Run cleanup on page load
    cleanupCorruptedText();

    // Specifically fix hero section H1 if corrupted
    const heroH1 = document.querySelector('#home h1');
    if (heroH1 && heroH1.textContent.includes('NaN')) {
        heroH1.innerHTML = `
            Rotaract Club of 
            <span class="block bg-gradient-to-r from-yellow-300 via-orange-300 to-red-300 bg-clip-text text-transparent animate-gradient-x">
                Zamboanga City North
            </span>
        `;
    }

    // Scroll progress indicator
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        window.addEventListener('scroll', () => {
            const scrolled = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
            scrollIndicator.style.transform = `scaleX(${scrolled / 100})`;
        });
    }

    // Enhanced parallax effect for hero section
    const heroSection = document.getElementById('home');
    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = heroSection.querySelector('.hero-content');
            if (parallax) {
                const speed = scrolled * 0.5;
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });
    }

    // Enhanced mobile menu toggle with better responsiveness
    const mobileMenu = document.querySelector('.mobile-menu');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenu && navLinks) {
        // Toggle mobile menu
        mobileMenu.addEventListener('click', () => {
            const isActive = mobileMenu.classList.toggle('active');
            navLinks.classList.toggle('hidden');
            
            // Animate hamburger menu with better transforms
            const spans = mobileMenu.querySelectorAll('span');
            if (isActive) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[1].style.transform = 'translateX(10px)';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
                
                // Add slide-in animation to mobile menu
                navLinks.style.opacity = '0';
                navLinks.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    navLinks.style.opacity = '1';
                    navLinks.style.transform = 'translateY(0)';
                }, 50);
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[1].style.transform = 'none';
                spans[2].style.transform = 'none';
            }
        });

        // Close mobile menu when clicking on nav links
        const mobileNavLinks = navLinks.querySelectorAll('a');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                navLinks.classList.add('hidden');
                
                // Reset hamburger menu
                const spans = mobileMenu.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[1].style.transform = 'none';
                spans[2].style.transform = 'none';
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !navLinks.contains(e.target) && mobileMenu.classList.contains('active')) {
                mobileMenu.classList.remove('active');
                navLinks.classList.add('hidden');
                
                // Reset hamburger menu
                const spans = mobileMenu.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[1].style.transform = 'none';
                spans[2].style.transform = 'none';
            }
        });

        // Handle window resize to properly close mobile menu on larger screens
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024 && mobileMenu.classList.contains('active')) {
                mobileMenu.classList.remove('active');
                navLinks.classList.add('hidden');
                
                // Reset hamburger menu
                const spans = mobileMenu.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[1].style.transform = 'none';
                spans[2].style.transform = 'none';
            }
        });
    }

    // Enhanced form validation and submission
    const contactForm = document.getElementById('contactForm');
    const formLoading = document.getElementById('formLoading');
    const formSuccess = document.getElementById('formSuccess');
    const formError = document.getElementById('formError');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnIcon = document.getElementById('btnIcon');

    // Real-time validation
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        const errorMessage = field.parentElement.querySelector('.error-message');
        let isValid = true;
        let message = '';

        // Remove existing styles
        field.classList.remove('border-red-300', 'border-green-300');
        errorMessage.classList.add('hidden');

        switch (fieldName) {
            case 'name':
                if (value.length < 2) {
                    message = 'Name must be at least 2 characters long';
                    isValid = false;
                }
                break;
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    message = 'Please enter a valid email address';
                    isValid = false;
                }
                break;
            case 'phone':
                if (value && !/^[\+]?[0-9\s\-\(\)]{10,}$/.test(value)) {
                    message = 'Please enter a valid phone number';
                    isValid = false;
                }
                break;
            case 'subject':
                if (value.length < 3) {
                    message = 'Subject must be at least 3 characters long';
                    isValid = false;
                }
                break;
            case 'message':
                if (value.length < 10) {
                    message = 'Message must be at least 10 characters long';
                    isValid = false;
                }
                break;
        }

        if (!isValid) {
            field.classList.add('border-red-300');
            errorMessage.textContent = message;
            errorMessage.classList.remove('hidden');
        } else if (value) {
            field.classList.add('border-green-300');
        }

        return isValid;
    }

    // Add real-time validation to form fields
    if (contactForm) {
        const formFields = contactForm.querySelectorAll('input, textarea');
        formFields.forEach(field => {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => {
                // Clear error state on input
                field.classList.remove('border-red-300');
                const errorMessage = field.parentElement.querySelector('.error-message');
                errorMessage.classList.add('hidden');
            });
        });

        // Enhanced form submission with Formspree
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Validate all fields
            let isFormValid = true;
            formFields.forEach(field => {
                if (!validateField(field) && field.hasAttribute('required')) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                // Shake the form to indicate errors
                contactForm.style.transform = 'translateX(-5px)';
                setTimeout(() => contactForm.style.transform = 'translateX(5px)', 100);
                setTimeout(() => contactForm.style.transform = 'translateX(0)', 200);
                return;
            }

            // Show loading state
            formLoading.classList.remove('hidden');
            formLoading.classList.add('flex');
            submitBtn.disabled = true;
            btnText.textContent = 'Sending...';
            btnIcon.className = 'fas fa-spinner fa-spin';

            try {
                const formData = new FormData(contactForm);
                
                // Add additional fields for better organization
                formData.append('_subject', `New Contact Form Submission from ${formData.get('name')}`);
                formData.append('_replyto', formData.get('email'));
                
                const response = await fetch(contactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Success
                    formSuccess.classList.remove('hidden');
                    formError.classList.add('hidden');
                    contactForm.reset();
                    formFields.forEach(field => {
                        field.classList.remove('border-green-300', 'border-red-300');
                    });
                    
                    // Show success notification
                    showNotification('Thank you! Your message has been sent successfully.', 'success');
                    
                    // Scroll to success message
                    formSuccess.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    throw new Error(data.error || 'Network response was not ok');
                }
            } catch (error) {
                // Error
                formError.classList.remove('hidden');
                formSuccess.classList.add('hidden');
                
                // Show error notification
                showNotification('Sorry, there was an error sending your message. Please try again.', 'error');
                
                console.error('Error:', error);
            } finally {
                // Reset loading state
                formLoading.classList.add('hidden');
                formLoading.classList.remove('flex');
                submitBtn.disabled = false;
                btnText.textContent = 'Send Message';
                btnIcon.className = 'fas fa-paper-plane';
            }
        });
    }
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                // Close mobile menu if open
                if (navLinks) {
                    navLinks.classList.add('hidden');
                }
                if (mobileMenu) {
                    mobileMenu.classList.remove('active');
                }
                
                // Smooth scroll to target
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Enhanced navbar scroll effect with About section detection
    const navbar = document.getElementById('navbar');
    const logoImg = navbar ? navbar.querySelector('img[src*="rotaract-logo"]') : null;
    let lastScrollY = window.scrollY;
    let ticking = false;

    function updateNavbar() {
        const currentScrollY = window.scrollY;
        const navbar = document.getElementById('navbar');
        const aboutSection = document.getElementById('about');
        
        if (!navbar) return;
        
        // Check if we've reached the About section
        const aboutOffsetTop = aboutSection ? aboutSection.offsetTop - 150 : 500;
        const hasReachedAbout = currentScrollY >= aboutOffsetTop;
        
        if (currentScrollY > 100) {
            if (currentScrollY > lastScrollY) {
                // Scrolling down - hide navbar
                navbar.style.transform = 'translate(-50%, -100%)';
                navbar.style.opacity = '0.9';
            } else {
                // Scrolling up - show navbar
                navbar.style.transform = 'translate(-50%, 0)';
                navbar.style.opacity = '1';
            }
            
            // Add compact styling on scroll
            navbar.classList.add('scrolled');
            
            // Add dark text class when reaching About section
            if (hasReachedAbout) {
                navbar.classList.add('dark-text');
                // Change logo to dark version
                if (logoImg && logoImg.src.includes('rotaract-logo.png')) {
                    logoImg.src = logoImg.src.replace('rotaract-logo.png', 'rotaract-logo-dark.png');
                }
            } else {
                navbar.classList.remove('dark-text');
                // Change logo back to light version
                if (logoImg && logoImg.src.includes('rotaract-logo-dark.png')) {
                    logoImg.src = logoImg.src.replace('rotaract-logo-dark.png', 'rotaract-logo.png');
                }
            }
        } else {
            // At top - always show
            navbar.style.transform = 'translate(-50%, 0)';
            navbar.style.opacity = '1';
            navbar.classList.remove('scrolled');
            navbar.classList.remove('dark-text');
            // Ensure logo is light version at top
            if (logoImg && logoImg.src.includes('rotaract-logo-dark.png')) {
                logoImg.src = logoImg.src.replace('rotaract-logo-dark.png', 'rotaract-logo.png');
            }
        }
        
        lastScrollY = currentScrollY;
        ticking = false;
    }

    function requestNavbarUpdate() {
        if (!ticking) {
            requestAnimationFrame(updateNavbar);
            ticking = true;
        }
    }

    window.addEventListener('scroll', requestNavbarUpdate, { passive: true });

    // Active navigation highlighting with improved detection
    function updateActiveNavigation() {
        const sections = document.querySelectorAll('section[id]');
        const navLinkElements = document.querySelectorAll('.nav-link[href^="#"]');
        const navbar = document.getElementById('navbar');
        
        let current = '';
        const scrollPosition = window.scrollY + 150; // Offset for better detection
        
        // Find current section
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        // Default to home if at the very top
        if (window.scrollY < 100) {
            current = 'home';
        }
        
        // Check if we're in the about section or beyond
        const aboutSection = document.getElementById('about');
        const isInAboutOrBeyond = aboutSection && scrollPosition >= aboutSection.offsetTop - 100;
        
        // Update active states for all navigation links
        navLinkElements.forEach(link => {
            const href = link.getAttribute('href');
            const isActive = href === `#${current}`;
            
            // Remove all active classes
            link.classList.remove('active', 'active-on-about');
            
            if (isActive) {
                if (isInAboutOrBeyond) {
                    link.classList.add('active-on-about');
                } else {
                    link.classList.add('active');
                }
            }
        });
        
        // Update navbar styling when reaching about section
        if (navbar) {
            if (isInAboutOrBeyond) {
                navbar.classList.add('in-about-section');
            } else {
                navbar.classList.remove('in-about-section');
            }
        }
    }

    // Debounced scroll handler for performance
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        scrollTimeout = setTimeout(updateActiveNavigation, 10);
    }, { passive: true });

    // Initial call to set active navigation
    updateActiveNavigation();

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeInUp');
                // Add staggered animation for child elements
                const children = entry.target.querySelectorAll('.animate-on-scroll');
                children.forEach((child, index) => {
                    setTimeout(() => {
                        child.classList.add('animate-fadeInUp');
                    }, index * 100);
                });
            }
        });
    }, observerOptions);

    // Observe sections and cards
    document.querySelectorAll('section, .card-hover, .officer-card, .project-card').forEach(element => {
        observer.observe(element);
    });

    // Gallery item interactions
    document.querySelectorAll('.gallery-item, [class*="gallery"]').forEach(item => {
        item.addEventListener('click', (e) => {
            if (item.classList.contains('cursor-pointer')) {
                // Add click animation
                item.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    item.style.transform = '';
                }, 150);
            }
        });
    });

    // Enhanced hover effects for interactive elements
    document.querySelectorAll('.group, .card-hover, .interactive').forEach(element => {
        element.addEventListener('mouseenter', () => {
            element.style.transform = 'translateY(-8px)';
        });
        
        element.addEventListener('mouseleave', () => {
            element.style.transform = 'translateY(0)';
        });
    });

    // Parallax effect for hero background elements
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('[class*="animate-pulse"]');
        
        parallaxElements.forEach((element, index) => {
            const speed = 0.5 + (index * 0.1);
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Initialize statistics counter animation
    const observeStats = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statsNumbers = entry.target.querySelectorAll('[class*="text-3xl"], [class*="text-4xl"]');
                statsNumbers.forEach(stat => {
                    animateNumber(stat);
                });
            }
        });
    });

    // Observe statistics section
    const statsSection = document.querySelector('#home');
    if (statsSection) {
        observeStats.observe(statsSection);
    }

    // Add keyboard navigation support
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Close mobile menu
            if (navLinks && !navLinks.classList.contains('hidden')) {
                navLinks.classList.add('hidden');
                mobileMenu?.classList.remove('active');
            }
        }
    });

    // Initialize lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});

// Utility Functions

// Show notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 10);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Animate numbers function
function animateNumber(element) {
    const target = parseInt(element.textContent.replace(/\D/g, ''));
    const duration = 2000;
    const steps = 60;
    const increment = target / steps;
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        
        const suffix = element.textContent.replace(/\d/g, '');
        element.textContent = Math.floor(current) + suffix;
    }, duration / steps);
}

// Debounce function for performance
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            timeout = null;
            if (!immediate) func(...args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func(...args);
    };
}

// Add smooth reveal animations on scroll
window.addEventListener('scroll', debounce(() => {
    const reveals = document.querySelectorAll('.animate-on-scroll');
    
    reveals.forEach(element => {
        const windowHeight = window.innerHeight;
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < windowHeight - elementVisible) {
            element.classList.add('animate-fadeInUp');
        }
    });
}, 10));

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform translate-x-full transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' :
                'fa-info-circle'
            } mr-3"></i>
            <span>${message}</span>
            <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Slide in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Initialize lazy loading for images
if ('IntersectionObserver' in window) {
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
}