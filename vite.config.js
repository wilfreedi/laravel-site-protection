import { defineConfig } from 'vite';
import obfuscatorPlugin from 'vite-plugin-obfuscator';
import obfuscator from 'rollup-plugin-obfuscator';

export default defineConfig({
    build: {
        outDir: 'public/site-protection/js', // Куда будут собираться файлы
        emptyOutDir: true, // Очистка папки перед сборкой
        rollupOptions: {
            input: 'resources/js/script.js', // Исходный JS
            output: {
                entryFileNames: '[name].js', // Имя собранного файла
            },
            plugins: [
                obfuscator({
                    compact: true,
                    controlFlowFlattening: true,
                    deadCodeInjection: true,
                    stringArrayEncoding: ['base64'],
                    disableConsoleOutput: true,
                }),
            ],
        },
    },
    root: '.', // Корень проекта
    publicDir: false, // Убираем стандартную публичную папку Vite
});
