import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    define: {
        __VUE_OPTIONS_API__: true,                // set to false only if you *never* use Options API
        __VUE_PROD_DEVTOOLS__: false,             // keep devtools out of prod
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false, // set true only if you want verbose hydration logs in prod
    },
});
