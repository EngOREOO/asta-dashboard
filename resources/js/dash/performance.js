// 🚀 Enhanced Performance Utilities for Admin Dashboard
// Smooth animations, lazy loading, and performance optimizations

// Intersection Observer for lazy loading with smooth animations
const createLazyLoader = () => {
    const lazyElements = document.querySelectorAll('[data-lazy]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const lazyType = element.dataset.lazy;
                
                // Add entrance animation
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                // Load content based on lazy type
                switch (lazyType) {
                    case 'image':
                        if (element.dataset.src) {
                            element.src = element.dataset.src;
                            element.classList.remove('opacity-0');
                        }
                        break;
                    case 'content':
                        element.classList.remove('opacity-0');
                        break;
                    case 'table':
                        loadTableData(element);
                        break;
                }
                
                // Smooth entrance animation
                requestAnimationFrame(() => {
                    element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                });
                
                observer.unobserve(element);
            }
        });
    }, {
        rootMargin: '50px',
        threshold: 0.1
    });
    
    lazyElements.forEach(el => observer.observe(el));
};

// Enhanced table data loading with smooth transitions
const loadTableData = (element) => {
    const loadingState = element.querySelector('[data-loading]');
    const contentState = element.querySelector('[data-content]');
    
    if (loadingState && contentState) {
        // Smooth loading transition
        loadingState.style.transition = 'all 0.3s ease-out';
        loadingState.style.opacity = '0';
        loadingState.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            loadingState.classList.add('hidden');
            contentState.classList.remove('hidden');
            
            // Stagger animation for table rows
            const rows = contentState.querySelectorAll('tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    row.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, index * 50);
            });
        }, 300);
    }
};

// Enhanced page transitions with loading states
const createPageTransitions = () => {
    const links = document.querySelectorAll('a[href^="/"], a[href^="http"]');
    
    links.forEach(link => {
        link.addEventListener('click', (e) => {
            // Skip external links and special links
            if (link.target === '_blank' || link.href.includes('javascript:')) {
                return;
            }
            
            // Add loading state to clicked link
            link.style.transition = 'all 0.2s ease-out';
            link.style.opacity = '0.6';
            link.style.transform = 'scale(0.98)';
            
            // Smooth page transition
            document.body.classList.add('page-transitioning');
            
            // Add loading indicator
            addLoadingIndicator();
        });
    });
};

// Add loading indicator for better UX
const addLoadingIndicator = () => {
    const existingIndicator = document.querySelector('.page-loading-indicator');
    if (existingIndicator) return;
    
    const indicator = document.createElement('div');
    indicator.className = 'page-loading-indicator fixed top-4 right-4 z-50 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg';
    indicator.innerHTML = `
        <div class="flex items-center space-x-2 rtl:space-x-reverse">
            <div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
            <span>جاري التحميل...</span>
        </div>
    `;
    
    document.body.appendChild(indicator);
    
    // Remove indicator after page load
    window.addEventListener('load', () => {
        if (indicator) {
            indicator.style.transition = 'all 0.3s ease-out';
            indicator.style.opacity = '0';
            indicator.style.transform = 'translateY(-10px)';
            setTimeout(() => indicator.remove(), 300);
        }
    });
};

// Enhanced performance monitoring
const createPerformanceMonitor = () => {
    if ('performance' in window) {
        // Monitor page load performance
        window.addEventListener('load', () => {
            const navigation = performance.getEntriesByType('navigation')[0];
            const loadTime = navigation.loadEventEnd - navigation.loadEventStart;
            
            if (loadTime > 3000) {
                console.warn('⚠️ Page load time is slow:', Math.round(loadTime), 'ms');
                // Show performance warning to user
                showPerformanceWarning(loadTime);
            } else {
                console.log('✅ Page loaded in:', Math.round(loadTime), 'ms');
            }
        });
        
        // Monitor Core Web Vitals
        if ('web-vital' in window) {
            webVitals.getCLS(console.log);
            webVitals.getFID(console.log);
            webVitals.getFCP(console.log);
            webVitals.getLCP(console.log);
            webVitals.getTTFB(console.log);
        }
    }
};

// Show performance warning to user
const showPerformanceWarning = (loadTime) => {
    const warning = document.createElement('div');
    warning.className = 'performance-warning fixed bottom-4 left-4 z-50 bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg shadow-lg';
    warning.innerHTML = `
        <div class="flex items-center space-x-2 rtl:space-x-reverse">
            <i class="ti ti-alert-triangle text-yellow-600"></i>
            <span>الصفحة بطيئة في التحميل (${Math.round(loadTime)}ms)</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-yellow-600 hover:text-yellow-800">
                <i class="ti ti-x"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(warning);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (warning.parentElement) {
            warning.style.transition = 'all 0.3s ease-out';
            warning.style.opacity = '0';
            warning.style.transform = 'translateY(10px)';
            setTimeout(() => warning.remove(), 300);
        }
    }, 5000);
};

// Enhanced smooth scroll utilities
const createSmoothScroll = () => {
    const scrollElements = document.querySelectorAll('[data-smooth-scroll]');
    
    scrollElements.forEach(element => {
        element.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(element.getAttribute('href'));
            
            if (target) {
                // Smooth scroll with easing
                target.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
                
                // Add highlight effect
                target.style.transition = 'all 0.3s ease-out';
                target.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
                target.style.borderRadius = '8px';
                
                setTimeout(() => {
                    target.style.backgroundColor = '';
                    target.style.borderRadius = '';
                }, 2000);
            }
        });
    });
};

// Card hover animations
const createCardAnimations = () => {
    const cards = document.querySelectorAll('.admin-card');
    
    cards.forEach(card => {
        // Add hover effects
        card.addEventListener('mouseenter', () => {
            card.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.transform = 'translateY(-4px) scale(1.02)';
            card.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
            card.style.boxShadow = '';
        });
        
        // Add click ripple effect
        card.addEventListener('click', (e) => {
            if (card.dataset.clickable === 'true') {
                createRippleEffect(e, card);
            }
        });
    });
};

// Create ripple effect for clickable cards
const createRippleEffect = (event, element) => {
    const ripple = document.createElement('span');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background: rgba(59, 130, 246, 0.3);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    `;
    
    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);
    
    setTimeout(() => ripple.remove(), 600);
};

// Add ripple animation CSS
const addRippleCSS = () => {
    if (!document.querySelector('#ripple-css')) {
        const style = document.createElement('style');
        style.id = 'ripple-css';
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
};

// Initialize all performance features
document.addEventListener('DOMContentLoaded', () => {
    createLazyLoader();
    createPageTransitions();
    createPerformanceMonitor();
    createSmoothScroll();
    createCardAnimations();
    addRippleCSS();
    
    console.log('🚀 Enhanced performance utilities initialized');
});

// Export for global use
window.DashboardPerformance = {
    createLazyLoader,
    createPageTransitions,
    createSmoothScroll,
    createCardAnimations
};
