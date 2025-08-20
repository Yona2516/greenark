// Main JavaScript for Greenark Consultants
class GreanarkWebsite {
    constructor() {
        this.init();
        this.bindEvents();
    }

    init() {
        this.setupMobileMenu();
        this.setupScrollEffects();
        this.setupAnimations();
        this.setupForms();
        this.loadProjects();
    }

    bindEvents() {
        // Mobile menu toggle
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const navLinks = document.querySelector('.nav-links');
        
        if (mobileToggle && navLinks) {
            mobileToggle.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                this.toggleMobileMenuIcon(mobileToggle);
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Close mobile menu when clicking on links
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                const navLinks = document.querySelector('.nav-links');
                const mobileToggle = document.querySelector('.mobile-menu-toggle');
                if (navLinks && mobileToggle) {
                    navLinks.classList.remove('active');
                    this.resetMobileMenuIcon(mobileToggle);
                }
            });
        });
    }

    setupMobileMenu() {
        // Handle mobile menu state
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        if (mobileToggle) {
            mobileToggle.innerHTML = `
                <span></span>
                <span></span>
                <span></span>
            `;
        }
    }

    toggleMobileMenuIcon(toggle) {
        const spans = toggle.querySelectorAll('span');
        spans[0].style.transform = toggle.classList.contains('active') ? 
            'rotate(45deg) translate(5px, 5px)' : 'rotate(0)';
        spans[1].style.opacity = toggle.classList.contains('active') ? '0' : '1';
        spans[2].style.transform = toggle.classList.contains('active') ? 
            'rotate(-45deg) translate(7px, -6px)' : 'rotate(0)';
        
        toggle.classList.toggle('active');
    }

    resetMobileMenuIcon(toggle) {
        const spans = toggle.querySelectorAll('span');
        spans[0].style.transform = 'rotate(0)';
        spans[1].style.opacity = '1';
        spans[2].style.transform = 'rotate(0)';
        toggle.classList.remove('active');
    }

    setupScrollEffects() {
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (header) {
                if (window.scrollY > 100) {
                    header.style.background = 'rgba(255, 255, 255, 0.98)';
                    header.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
                } else {
                    header.style.background = 'rgba(255, 255, 255, 0.95)';
                    header.style.boxShadow = 'none';
                }
            }
        });
    }

    setupAnimations() {
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe elements for animations
        document.querySelectorAll('.card, .project-item, .testimonial').forEach(el => {
            observer.observe(el);
        });
    }

    setupForms() {
        // Contact form
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleContactForm(contactForm);
            });
        }

        // Quote form
        const quoteForm = document.getElementById('quote-form');
        if (quoteForm) {
            quoteForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleQuoteForm(quoteForm);
            });
        }
    }

    async handleContactForm(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        try {
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');

            const formData = new FormData(form);
            const response = await fetch('/api/contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showAlert('Message sent successfully! We\'ll get back to you soon.', 'success');
                form.reset();
            } else {
                throw new Error(result.message || 'Failed to send message');
            }
        } catch (error) {
            console.error('Contact form error:', error);
            this.showAlert('Failed to send message. Please try again.', 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
        }
    }

    async handleQuoteForm(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        try {
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');

            const formData = new FormData(form);
            const response = await fetch('/api/quotes', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showAlert(`Quote request submitted successfully! Reference: ${result.data.reference_number}`, 'success');
                form.reset();
            } else {
                throw new Error(result.message || 'Failed to submit quote request');
            }
        } catch (error) {
            console.error('Quote form error:', error);
            this.showAlert('Failed to submit quote request. Please try again.', 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
        }
    }

    async loadProjects() {
        try {
            const response = await fetch('/api/projects/featured');
            const result = await response.json();
            
            if (result.success) {
                this.displayFeaturedProjects(result.data);
            }
        } catch (error) {
            console.error('Failed to load projects:', error);
        }
    }

    displayFeaturedProjects(projects) {
        const container = document.getElementById('featured-projects');
        if (!container || !projects.length) return;

        const html = projects.map(project => `
            <div class="project-item card">
                <div class="project-image">
                    ${project.gallery && project.gallery.length > 0 ? 
                        `<img src="/storage/${project.gallery[0]}" alt="${project.title}" loading="lazy">` : 
                        '<div class="project-placeholder"></div>'
                    }
                </div>
                <div class="project-content">
                    <h3>${project.title}</h3>
                    <p class="project-category">${this.formatCategory(project.category)}</p>
                    <p class="project-description">${project.short_description}</p>
                    <a href="/portfolio/${project.slug}" class="btn btn-primary">View Details</a>
                </div>
            </div>
        `).join('');

        container.innerHTML = html;
    }

    formatCategory(category) {
        const categories = {
            'residential': 'Residential',
            'commercial': 'Commercial',
            'custom-builds': 'Custom Builds',
            'renovations': 'Renovations'
        };
        return categories[category] || category;
    }

    showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // Create new alert
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;

        // Add to top of main content
        const main = document.querySelector('main') || document.body;
        main.insertBefore(alert, main.firstChild);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);

        // Scroll to top to show alert
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Utility methods
    debounce(func, wait) {
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

    throttle(func, limit) {
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
}

// API utility class
class APIClient {
    constructor(baseUrl = '/api') {
        this.baseUrl = baseUrl;
    }

    async get(endpoint) {
        const response = await fetch(`${this.baseUrl}${endpoint}`);
        return this.handleResponse(response);
    }

    async post(endpoint, data) {
        const response = await fetch(`${this.baseUrl}${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });
        return this.handleResponse(response);
    }

    async postFormData(endpoint, formData) {
        const response = await fetch(`${this.baseUrl}${endpoint}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        return this.handleResponse(response);
    }

    async handleResponse(response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new GreanarkWebsite();
    window.apiClient = new APIClient();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { GreanarkWebsite, APIClient };
}