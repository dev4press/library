import { mkdirSync, readdirSync, rmSync, writeFileSync } from 'node:fs';
import { basename, dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import * as sass from 'sass';
import { defineConfig } from 'vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';

const root = dirname(fileURLToPath(import.meta.url));
const scriptInputs = Object.fromEntries(
    readdirSync(resolve(root, 'src/scripts'), {withFileTypes: true})
        .filter((entry) => entry.isFile() && entry.name.endsWith('.js'))
        .map((entry) => [
            entry.name.replace(/\.js$/, ''),
            resolve(root, 'src/scripts', entry.name)
        ])
);
const scssInputs = readdirSync(resolve(root, 'src/scss'), {withFileTypes: true})
    .filter((entry) => entry.isFile() && entry.name.endsWith('.scss') && !entry.name.startsWith('_'))
    .map((entry) => resolve(root, 'src/scss', entry.name));

const buildStyles = {
    name: 'build-library-styles',
    apply: 'build',
    buildStart() {
        const outputDir = resolve(root, 'resources/css');

        rmSync(outputDir, {recursive: true, force: true});
        mkdirSync(outputDir, {recursive: true});

        scssInputs.forEach((input) => {
            const result = sass.compile(input, {style: 'compressed'});
            const output = resolve(outputDir, `${basename(input, '.scss')}.min.css`);

            writeFileSync(output, result.css);
        });
    }
};

export default defineConfig({
    plugins: [
        buildStyles,
        viteStaticCopy({
            targets: [
                {
                    src: 'src/gfx/**/*',
                    dest: '../gfx',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'src/flags/css/*',
                    dest: 'flags/css',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'src/flags/img/*',
                    dest: 'flags/img',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'node_modules/fitvids/dist/fitvids.min.js',
                    dest: 'fitvids',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'node_modules/js-cookie/dist/js.cookie.min.js',
                    dest: 'js-cookie',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'node_modules/mark.js/dist/mark.min.js',
                    dest: 'mark-js',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'node_modules/@dev4press/field-up/dist/field-up.css',
                    dest: 'field-up',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'node_modules/@dev4press/field-up/dist/field-up.umd.js',
                    dest: 'field-up',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'node_modules/@dev4press/flyin/dist/flyin.css',
                    dest: 'flyin',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'node_modules/@dev4press/flyin/dist/flyin.umd.js',
                    dest: 'flyin',
                    rename: {
                        stripBase: true
                    }
                },
            ]
        })
    ],
    build: {
        outDir: 'resources/dist',
        emptyOutDir: true,
        minify: 'esbuild',
        rollupOptions: {
            input: scriptInputs,
            output: {
                entryFileNames: '[name].min.js'
            }
        }
    }
});