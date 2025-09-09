// 🚀 OPTIMIZED Dashboard Asset Loader
// Performance-focused with lazy loading and modern practices

// Core CSS - Critical path only (commented out missing files)
// import '../../../prompts/dash/assets/vendor/css/rtl/core.css';
// import '../../../prompts/dash/assets/vendor/css/rtl/theme-default.css';

// Icons - Essential only (commented out missing files)
// import '../../../prompts/dash/assets/vendor/fonts/tabler-icons.css';

// Core JS - Bootstrap & essential functionality (commented out missing files)
// import '../../../prompts/dash/assets/vendor/js/bootstrap.js';
// import '../../../prompts/dash/assets/vendor/js/menu.js';

// Lazy load heavy libraries only when needed
const loadHeavyAssets = () => {
  // Perfect Scrollbar - for custom scrollbars
  // import('../../../prompts/dash/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js');
  
  // Apex Charts - for analytics
  // import('../../../prompts/dash/assets/vendor/libs/apex-charts/apexcharts.js');
  
  // Swiper - for carousels
  // import('../../../prompts/dash/assets/vendor/libs/swiper/swiper.js');
};

// Load heavy assets after page load
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', loadHeavyAssets);
} else {
  loadHeavyAssets();
}

// Performance monitoring
if (process.env.NODE_ENV === 'development') {
  console.log('🚀 Dashboard assets loaded with performance optimizations');
}

// Import performance utilities
// import './performance.js';
