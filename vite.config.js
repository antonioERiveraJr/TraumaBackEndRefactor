import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    define: {
        // Define the feature flag
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(true),
    },
});