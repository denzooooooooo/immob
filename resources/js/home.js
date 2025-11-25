// Home page JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scrolling for anchor links
    initSmoothScrolling();
    
    // Search form enhancements
    initSearchForm();
    
    // Favorite buttons functionality
    initFavoriteButtons();
    
    // Property cards hover effects
    initPropertyCards();
    
    // Statistics counter animation
    initStatsAnimation();
    
    // Scroll to top functionality
    initScrollToTop();
    
    // Mobile menu toggle (if needed)
    initMobileMenu();
    
    // Form validation
    initFormValidation();
    
    // Property carousel for featured properties
    initPropertyCarousel();
    
    // Advanced search with suggestions
    initAdvancedSearch();
    
    // Testimonials carousel
    initTestimonialsCarousel();
});

/**
 * Initialize smooth scrolling for anchor links
 */
function initSmoothScrolling() {
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector('.statistics-section') || 
                          document.querySelector('section:nth-child(2)');
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }
}

/**
 * Initialize search form enhancements
 */
function initSearchForm() {
    const searchForm = document.querySelector('form[action*="search"]');
    if (!searchForm) return;
    
    // Add loading state to search button
    searchForm.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Recherche...';
            submitBtn.disabled = true;
            
            // Re-enable after 3 seconds (fallback)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        }
    });
    
    // Enhanced select interactions
    const selects = searchForm.querySelectorAll('select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.classList.add('has-value');
        });
        
        // Add initial class if has value
        if (select.value) {
            select.classList.add('has-value');
        }
    });
}

/**
 * Initialize favorite buttons functionality
 */
function initFavoriteButtons() {
    const favoriteButtons = document.querySelectorAll('.favorite-btn, button[class*="heart"]');
    
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const icon = this.querySelector('i');
            const propertyId = this.dataset.propertyId;
            
            if (!propertyId) {
                console.warn('Property ID not found for favorite button');
                return;
            }
            
            // Toggle visual state immediately
            this.classList.toggle('favorited');
            if (icon) {
                icon.classList.toggle('fas');
                icon.classList.toggle('far');
            }
            
            // Send AJAX request
            toggleFavorite(propertyId, this);
        });
    });
}

/**
 * Toggle favorite status via AJAX
 */
function toggleFavorite(propertyId, button) {
    if (!window.axios) {
        console.error('Axios not available');
        return;
    }
    
    const url = `/api/properties/${propertyId}/favorite`;
    
    window.axios.post(url, {}, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    })
    .then(response => {
        if (response.data.success) {
            // Update button state based on response
            const isFavorited = response.data.favorited;
            button.classList.toggle('favorited', isFavorited);
            
            const icon = button.querySelector('i');
            if (icon) {
                icon.className = isFavorited ? 'fas fa-heart' : 'far fa-heart';
            }
            
            // Show success message
            showNotification(
                isFavorited ? 'Ajouté aux favoris' : 'Retiré des favoris',
                'success'
            );
        }
    })
    .catch(error => {
        console.error('Error toggling favorite:', error);
        
        // Revert visual state on error
        button.classList.toggle('favorited');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('fas');
            icon.classList.toggle('far');
        }
        
        showNotification('Erreur lors de la mise à jour des favoris', 'error');
    });
}

/**
 * Initialize property cards hover effects and interactions
 */
function initPropertyCards() {
    const propertyCards = document.querySelectorAll('.property-card, [class*="property"]');
    
    propertyCards.forEach(card => {
        // Add hover effect for better UX
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        // Handle card clicks (excluding favorite buttons)
        card.addEventListener('click', function(e) {
            // Don't navigate if clicking on favorite button or other interactive elements
            if (e.target.closest('.favorite-btn, button, a')) {
                return;
            }
            
            const link = this.querySelector('a[href*="properties"]');
            if (link) {
                window.location.href = link.href;
            }
        });
    });
}

/**
 * Initialize statistics counter animation
 */
function initStatsAnimation() {
    const statsSection = document.querySelector('.statistics-section, [class*="stats"]');
    if (!statsSection) return;
    
    const counters = statsSection.querySelectorAll('[class*="text-4xl"], .stat-number');
    
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    counters.forEach(counter => {
        observer.observe(counter);
    });
}

/**
 * Animate counter numbers
 */
function animateCounter(element) {
    const target = parseInt(element.textContent.replace(/\D/g, ''));
    if (isNaN(target)) return;
    
    const duration = 2000; // 2 seconds
    const step = target / (duration / 16); // 60fps
    let current = 0;
    
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        
        element.textContent = Math.floor(current).toLocaleString('fr-FR');
    }, 16);
}

/**
 * Initialize scroll to top functionality
 */
function initScrollToTop() {
    // Create scroll to top button
    const scrollBtn = document.createElement('button');
    scrollBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
    scrollBtn.className = 'scroll-to-top fixed bottom-8 right-8 bg-gabon-blue text-white w-12 h-12 rounded-full shadow-lg opacity-0 transition-opacity duration-300 z-50 hover:bg-gabon-green';
    scrollBtn.style.display = 'none';
    document.body.appendChild(scrollBtn);
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollBtn.style.display = 'flex';
            scrollBtn.style.alignItems = 'center';
            scrollBtn.style.justifyContent = 'center';
            setTimeout(() => scrollBtn.style.opacity = '1', 10);
        } else {
            scrollBtn.style.opacity = '0';
            setTimeout(() => scrollBtn.style.display = 'none', 300);
        }
    });
    
    // Scroll to top on click
    scrollBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Initialize mobile menu toggle
 */
function initMobileMenu() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn, [data-mobile-menu]');
    const mobileMenu = document.querySelector('.mobile-menu, [data-mobile-menu-content]');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenuBtn.classList.toggle('active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                mobileMenuBtn.classList.remove('active');
            }
        });
    }
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => clearFieldError(input));
        });
        
        form.addEventListener('submit', (e) => {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Veuillez corriger les erreurs dans le formulaire', 'error');
            }
        });
    });
}

/**
 * Validate individual form field
 */
function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'Ce champ est requis';
    }
    
    // Email validation
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = 'Adresse email invalide';
        }
    }
    
    // Phone validation
    if (field.type === 'tel' && value) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
        if (!phoneRegex.test(value)) {
            isValid = false;
            errorMessage = 'Numéro de téléphone invalide';
        }
    }
    
    // Show/hide error
    if (!isValid) {
        showFieldError(field, errorMessage);
    } else {
        clearFieldError(field);
    }
    
    return isValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('border-red-500');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-red-500 text-sm mt-1';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.classList.remove('border-red-500');
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

/**
 * Show notification message
 */
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
    
    // Set colors based on type
    switch (type) {
        case 'success':
            notification.classList.add('bg-green-500', 'text-white');
            break;
        case 'error':
            notification.classList.add('bg-red-500', 'text-white');
            break;
        case 'warning':
            notification.classList.add('bg-yellow-500', 'text-gray-900');
            break;
        default:
            notification.classList.add('bg-blue-500', 'text-white');
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button class="ml-4 text-current opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

/**
 * Utility function to debounce function calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Utility function to throttle function calls
 */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

/**
 * Initialize property carousel
 */
function initPropertyCarousel() {
    const container = document.querySelector('.featured-properties');
    if (!container) return;

    const properties = container.querySelectorAll('.property-card');
    if (properties.length <= 1) return;

    let currentIndex = 0;
    let isAnimating = false;

    // Add navigation
    const nav = document.createElement('div');
    nav.className = 'flex justify-center items-center space-x-4 mt-8';
    nav.innerHTML = `
        <button class="prev-btn bg-gabon-blue text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gabon-green transition-colors duration-200">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="flex space-x-2"></div>
        <button class="next-btn bg-gabon-blue text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gabon-green transition-colors duration-200">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;
    container.after(nav);

    // Add dots
    const dotsContainer = nav.querySelector('div');
    properties.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.className = `w-3 h-3 rounded-full transition-colors duration-200 ${i === 0 ? 'bg-gabon-blue' : 'bg-gray-300'}`;
        dot.addEventListener('click', () => goToSlide(i));
        dotsContainer.appendChild(dot);
    });

    // Add click handlers
    nav.querySelector('.prev-btn').addEventListener('click', prev);
    nav.querySelector('.next-btn').addEventListener('click', next);

    // Setup autoplay
    let autoplayTimer = setInterval(next, 5000);

    function updateSlide() {
        if (isAnimating) return;
        isAnimating = true;

        properties.forEach((prop, i) => {
            prop.style.transition = 'all 0.5s ease-in-out';
            if (i === currentIndex) {
                prop.style.opacity = '1';
                prop.style.transform = 'scale(1)';
            } else {
                prop.style.opacity = '0.5';
                prop.style.transform = 'scale(0.95)';
            }
        });

        // Update dots
        dotsContainer.querySelectorAll('button').forEach((dot, i) => {
            dot.className = `w-3 h-3 rounded-full transition-colors duration-200 ${i === currentIndex ? 'bg-gabon-blue' : 'bg-gray-300'}`;
        });

        setTimeout(() => isAnimating = false, 500);
    }

    function next() {
        currentIndex = (currentIndex + 1) % properties.length;
        updateSlide();
    }

    function prev() {
        currentIndex = (currentIndex - 1 + properties.length) % properties.length;
        updateSlide();
    }

    function goToSlide(index) {
        currentIndex = index;
        updateSlide();
        clearInterval(autoplayTimer);
        autoplayTimer = setInterval(next, 5000);
    }

    // Initialize first slide
    updateSlide();

    // Pause autoplay on hover
    container.addEventListener('mouseenter', () => clearInterval(autoplayTimer));
    container.addEventListener('mouseleave', () => {
        clearInterval(autoplayTimer);
        autoplayTimer = setInterval(next, 5000);
    });
}

/**
 * Initialize testimonials carousel
 */
function initTestimonialsCarousel() {
    const container = document.querySelector('.testimonials-section');
    if (!container) return;

    const testimonials = container.querySelectorAll('.testimonial');
    if (testimonials.length <= 1) return;

    let currentIndex = 0;
    let isAnimating = false;

    // Add navigation dots
    const dotsContainer = document.createElement('div');
    dotsContainer.className = 'flex justify-center space-x-2 mt-8';
    testimonials.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.className = `w-3 h-3 rounded-full transition-colors duration-200 ${i === 0 ? 'bg-gabon-blue' : 'bg-gray-300'}`;
        dot.addEventListener('click', () => goToSlide(i));
        dotsContainer.appendChild(dot);
    });
    container.appendChild(dotsContainer);

    function updateSlide() {
        if (isAnimating) return;
        isAnimating = true;

        testimonials.forEach((testimonial, i) => {
            testimonial.style.transition = 'all 0.5s ease-in-out';
            if (i === currentIndex) {
                testimonial.style.opacity = '1';
                testimonial.style.transform = 'translateX(0)';
            } else {
                testimonial.style.opacity = '0';
                testimonial.style.transform = 'translateX(100px)';
            }
        });

        // Update dots
        dotsContainer.querySelectorAll('button').forEach((dot, i) => {
            dot.className = `w-3 h-3 rounded-full transition-colors duration-200 ${i === currentIndex ? 'bg-gabon-blue' : 'bg-gray-300'}`;
        });

        setTimeout(() => isAnimating = false, 500);
    }

    function goToSlide(index) {
        currentIndex = index;
        updateSlide();
    }

    // Initialize first slide
    updateSlide();

    // Autoplay
    setInterval(() => {
        currentIndex = (currentIndex + 1) % testimonials.length;
        updateSlide();
    }, 5000);
}

/**
 * Initialize advanced search with suggestions
 */
function initAdvancedSearch() {
    const searchForm = document.querySelector('form[action*="search"]');
    if (!searchForm) return;

    // Add price range inputs
    const priceContainer = document.createElement('div');
    priceContainer.className = 'grid grid-cols-2 gap-4';
    priceContainer.innerHTML = `
        <div class="relative">
            <input type="number" name="min_price" placeholder="Prix minimum" 
                   class="w-full px-4 py-3 rounded-lg bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-gabon-yellow transition-all duration-200">
        </div>
        <div class="relative">
            <input type="number" name="max_price" placeholder="Prix maximum"
                   class="w-full px-4 py-3 rounded-lg bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-gabon-yellow transition-all duration-200">
        </div>
    `;
    searchForm.insertBefore(priceContainer, searchForm.querySelector('button[type="submit"]'));

    // Add surface area inputs
    const surfaceContainer = document.createElement('div');
    surfaceContainer.className = 'grid grid-cols-2 gap-4';
    surfaceContainer.innerHTML = `
        <div class="relative">
            <input type="number" name="min_surface" placeholder="Surface min (m²)" 
                   class="w-full px-4 py-3 rounded-lg bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-gabon-yellow transition-all duration-200">
        </div>
        <div class="relative">
            <input type="number" name="max_surface" placeholder="Surface max (m²)"
                   class="w-full px-4 py-3 rounded-lg bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-gabon-yellow transition-all duration-200">
        </div>
    `;
    searchForm.insertBefore(surfaceContainer, searchForm.querySelector('button[type="submit"]'));

    // Add bedrooms and bathrooms selects
    const roomsContainer = document.createElement('div');
    roomsContainer.className = 'grid grid-cols-2 gap-4';
    roomsContainer.innerHTML = `
        <select name="bedrooms" class="w-full px-4 py-3 rounded-lg bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-gabon-yellow transition-all duration-200">
            <option value="">Chambres</option>
            ${[1,2,3,4,5,'+5'].map(n => `<option value="${n}">${n} ${n === '+5' ? 'ou plus' : ''}</option>`).join('')}
        </select>
        <select name="bathrooms" class="w-full px-4 py-3 rounded-lg bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-gabon-yellow transition-all duration-200">
            <option value="">Salles de bain</option>
            ${[1,2,3,4,'+4'].map(n => `<option value="${n}">${n} ${n === '+4' ? 'ou plus' : ''}</option>`).join('')}
        </select>
    `;
    searchForm.insertBefore(roomsContainer, searchForm.querySelector('button[type="submit"]'));

    // Add more filters button
    const moreFiltersBtn = document.createElement('button');
    moreFiltersBtn.type = 'button';
    moreFiltersBtn.className = 'text-gabon-blue hover:text-gabon-green transition-colors duration-200 text-sm font-medium flex items-center';
    moreFiltersBtn.innerHTML = `
        <i class="fas fa-sliders-h mr-2"></i>
        Plus de filtres
    `;
    searchForm.insertBefore(moreFiltersBtn, searchForm.querySelector('button[type="submit"]'));

    // Initialize city autocomplete
    initCityAutocomplete();
}

/**
 * Initialize city autocomplete
 */
function initCityAutocomplete() {
    const citySelect = document.querySelector('select[name="city"]');
    if (!citySelect) return;

    const wrapper = document.createElement('div');
    wrapper.className = 'relative';
    citySelect.parentNode.insertBefore(wrapper, citySelect);

    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = citySelect.className;
    searchInput.placeholder = 'Rechercher une ville...';

    const suggestionsList = document.createElement('div');
    suggestionsList.className = 'absolute w-full bg-white mt-1 rounded-lg shadow-lg hidden z-50 max-h-60 overflow-y-auto';

    wrapper.appendChild(searchInput);
    wrapper.appendChild(suggestionsList);
    citySelect.style.display = 'none';

    // Handle input changes
    searchInput.addEventListener('input', debounce((e) => {
        const value = e.target.value.toLowerCase();
        const options = Array.from(citySelect.options);
        const filteredOptions = options.filter(option => 
            option.text.toLowerCase().includes(value)
        );

        suggestionsList.innerHTML = '';
        if (filteredOptions.length > 0 && value) {
            suggestionsList.classList.remove('hidden');
            filteredOptions.forEach(option => {
                const div = document.createElement('div');
                div.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                div.textContent = option.text;
                div.addEventListener('click', () => {
                    searchInput.value = option.text;
                    citySelect.value = option.value;
                    suggestionsList.classList.add('hidden');
                });
                suggestionsList.appendChild(div);
            });
        } else {
            suggestionsList.classList.add('hidden');
        }
    }, 200));

    // Close suggestions on click outside
    document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target)) {
            suggestionsList.classList.add('hidden');
        }
    });
}

// Export functions for use in other scripts if needed
window.MonnkamaHome = {
    showNotification,
    toggleFavorite,
    debounce,
    throttle,
    initPropertyCarousel,
    initTestimonialsCarousel,
    initAdvancedSearch
};
