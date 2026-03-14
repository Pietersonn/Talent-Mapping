// Public JavaScript for TalentMapping

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initializeAnimations();
    initializeUtilities();
});


// === ANIMATIONS ===
function initializeAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');

                // Stagger animation for grid items
                const gridItems = entry.target.querySelectorAll('.competency-card, .talent-competency-card');
                gridItems.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.animationDelay = `${index * 0.1}s`;
                        item.classList.add('animate-in');
                    }, index * 100);
                });
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('section, .competency-card, .talent-competency-card').forEach(el => {
        observer.observe(el);
    });

    // Number counter animation
    animateCounters();
}

function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');

    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/\D/g, ''));
        const suffix = counter.textContent.replace(/[0-9]/g, '');
        let current = 0;
        const increment = target / 50;

        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current) + suffix;
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target + suffix;
            }
        };

        // Start animation when element comes into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.disconnect();
                }
            });
        });

        observer.observe(counter);
    });
}

// === UTILITY FUNCTIONS ===
function initializeUtilities() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Copy to clipboard functionality
    const copyButtons = document.querySelectorAll('[data-copy]');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            navigator.clipboard.writeText(textToCopy).then(() => {
                showToast('Copied to clipboard!', 'success');
            });
        });
    });

    // Form enhancements
    enhanceForms();
}

function enhanceForms() {
    // Add floating label effect
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach(group => {
        const input = group.querySelector('input, textarea, select');
        const label = group.querySelector('label');

        if (input && label) {
            input.addEventListener('focus', () => {
                group.classList.add('focused');
            });

            input.addEventListener('blur', () => {
                if (!input.value) {
                    group.classList.remove('focused');
                }
            });

            // Check if input already has value
            if (input.value) {
                group.classList.add('focused');
            }
        }
    });

    // Password strength indicator
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        if (input.getAttribute('data-strength') !== 'false') {
            addPasswordStrengthIndicator(input);
        }
    });
}

function addPasswordStrengthIndicator(input) {
    const indicator = document.createElement('div');
    indicator.className = 'password-strength';
    indicator.innerHTML = `
        <div class="strength-bar">
            <div class="strength-fill"></div>
        </div>
        <div class="strength-text">Password strength</div>
    `;

    input.parentNode.insertBefore(indicator, input.nextSibling);

    input.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        const fill = indicator.querySelector('.strength-fill');
        const text = indicator.querySelector('.strength-text');

        fill.style.width = `${strength.percentage}%`;
        fill.className = `strength-fill ${strength.class}`;
        text.textContent = strength.text;
    });
}

function calculatePasswordStrength(password) {
    let score = 0;
    let feedback = [];

    if (password.length >= 8) score += 25;
    else feedback.push('At least 8 characters');

    if (/[a-z]/.test(password)) score += 25;
    else feedback.push('Lowercase letter');

    if (/[A-Z]/.test(password)) score += 25;
    else feedback.push('Uppercase letter');

    if (/[0-9]/.test(password)) score += 25;
    else feedback.push('Number');

    if (/[^A-Za-z0-9]/.test(password)) score += 10;

    let strength = 'weak';
    let className = 'weak';

    if (score >= 85) {
        strength = 'Very strong';
        className = 'very-strong';
    } else if (score >= 60) {
        strength = 'Strong';
        className = 'strong';
    } else if (score >= 40) {
        strength = 'Medium';
        className = 'medium';
    }

    return {
        percentage: Math.min(score, 100),
        text: strength,
        class: className,
        feedback: feedback
    };
}

// === NOTIFICATION SYSTEM ===
function showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-message">${message}</span>
            <button class="toast-close" onclick="this.parentNode.parentNode.remove()">×</button>
        </div>
    `;

    // Add to page
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }

    toastContainer.appendChild(toast);

    // Auto remove
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, duration);
}

// === LOADING STATES ===
function showLoading(element, text = 'Loading...') {
    element.disabled = true;
    element.dataset.originalText = element.textContent;
    element.innerHTML = `
        <span class="loading-spinner"></span>
        ${text}
    `;
}

function hideLoading(element) {
    element.disabled = false;
    element.textContent = element.dataset.originalText || 'Submit';
}

// === KEYBOARD SHORTCUTS ===
document.addEventListener('keydown', function(e) {
    // Escape key to close modals/dropdowns
    if (e.key === 'Escape') {
        const dropdown = document.querySelector('.user-dropdown.show');
        if (dropdown) {
            dropdown.classList.remove('show');
        }
    }

    // Ctrl+K for search (if implemented)
    if (e.ctrlKey && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// === EXPORT GLOBAL FUNCTIONS ===
window.TalentMapping = {
    toggleUserDropdown,
    showToast,
    showLoading,
    hideLoading
};
