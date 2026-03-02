import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/sass/app.scss',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
        },
    },
    server: {
        port: 5173,
        host: true,
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        outDir: 'public/build',
        manifest: true,
        manifestPath: path.resolve(__dirname, 'public/build/manifest.json'),
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: 'assets/[name].[hash].js',
                chunkFileNames: 'assets/[name].[hash].js',
                assetFileNames: 'assets/[name].[hash].[ext]',
            },
        },
    },
});

// Copy manifest.json to root of build folder for Laravel compatibility
const manifestSource = path.resolve(__dirname, 'public/build/.vite/manifest.json');
const manifestDest = path.resolve(__dirname, 'public/build/manifest.json');

// Run after build
const originalBuild = process.exit;
process.exit = function(code) {
    if (fs.existsSync(manifestSource)) {
        fs.copyFileSync(manifestSource, manifestDest);
        console.log('✓ Manifest copied to public/build/manifest.json');
    }
    return originalBuild.apply(this, arguments);
};

