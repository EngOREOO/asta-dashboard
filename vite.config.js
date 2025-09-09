import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/dash.css', 
                'resources/js/dash/assets.js'
            ],
            refresh: true
        }),
        vue(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    build: {
        // Production optimizations
        minify: 'esbuild', // Use esbuild instead of terser for better compatibility
        sourcemap: false, // Disable sourcemaps in production
        rollupOptions: {
            output: {
                // Optimize chunk splitting
                manualChunks: {
                    vendor: ['jquery'],
                    dashboard: ['resources/js/dash/assets.js'],
                },
                // Optimize asset naming for caching
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.')
                    const ext = info[info.length - 1]
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(ext)) {
                        return `assets/images/[name]-[hash].${ext}`
                    }
                    if (/css/i.test(ext)) {
                        return `assets/css/[name]-[hash].${ext}`
                    }
                    return `assets/[name]-[hash].${ext}`
                }
            }
        },
        // Optimize chunk size warnings
        chunkSizeWarningLimit: 1000,
        // Enable CSS code splitting
        cssCodeSplit: true,
        // Optimize assets
        assetsInlineLimit: 4096,
    },
    // Development optimizations
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ['jquery'],
    },
});
