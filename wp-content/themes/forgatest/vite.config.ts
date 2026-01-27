import { defineConfig, loadEnv } from 'vite';
import { resolve } from 'path';
import path from 'path';
import fs from 'fs';
import tailwindcss from '@tailwindcss/vite';
import os from 'os'

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        plugins: [
            tailwindcss(),
        ],
        resolve: {
            alias: {
                '@': path.resolve(__dirname, './'),
            },
        },
        root: '.',
        build: {
            outDir: 'dist',
            assetsDir: 'assets',
            rollupOptions: {
                input: [
                    resolve(__dirname, 'resources/index.ts'),
                ],
                output: {
                    format: 'es',
                    entryFileNames: '[name].js',
                    assetFileNames: ({ name }) => {
                        if (name && name.endsWith('.css')) {
                            return 'style.css';
                        }
                        return 'assets/[name].[ext]';
                    },
                },
            },
        },
        server: {
            host: env.VITE_DOMAIN,
            port: 5173,
            strictPort: true,
            cors: true,
            https: {
                key: fs.readFileSync(resolve(os.homedir(), `Library/Application Support/Herd/config/valet/Certificates/${env.VITE_DOMAIN}.key`)),
                cert: fs.readFileSync(resolve(os.homedir(), `Library/Application Support/Herd/config/valet/Certificates/${env.VITE_DOMAIN}.crt`)),
            },
            proxy: {
                '/wp-content': {
                    target: env.VITE_URL,
                    changeOrigin: true,
                    secure: false,
                },
            },
        },
    }
});