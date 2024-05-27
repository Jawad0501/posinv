import { defineConfig } from 'vite';
import path from 'path'
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/frontend/app.js',
                'resources/css/frontend.css',

                'resources/js/backend/app.js',
                'resources/css/backend.css',
                'resources/sass/backend/app.scss',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: (['resources/assets/backend/']),
                    dest: 'assets/'
                }
            ]
        })
    ],
    resolve: {
        alias: {
          "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
        },
    },
});
