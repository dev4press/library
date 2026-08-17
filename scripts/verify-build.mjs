import assert from 'node:assert/strict';
import {readdirSync, readFileSync} from 'node:fs';
import {dirname, resolve} from 'node:path';
import {fileURLToPath} from 'node:url';

const root = dirname(dirname(fileURLToPath(import.meta.url)));
const scssDir = resolve(root, 'src/scss');
const cssDir = resolve(root, 'resources/css');
const distDir = resolve(root, 'resources/dist');

const scssEntries = readdirSync(scssDir, {withFileTypes: true})
    .filter((entry) => entry.isFile() && entry.name.endsWith('.scss') && !entry.name.startsWith('_'))
    .map((entry) => entry.name.replace(/\.scss$/, '.min.css'))
    .sort();
const cssFiles = readdirSync(cssDir).sort();

assert.deepEqual(cssFiles, scssEntries);
cssFiles.forEach((file) => {
    assert.match(file, /\.min\.css$/);
    assert.doesNotMatch(readFileSync(resolve(cssDir, file), 'utf8'), /\r?\n/);
});

assert.deepEqual(readdirSync(resolve(distDir, 'field-up')).sort(), [
    'field-up.css',
    'field-up.umd.js'
]);
assert.deepEqual(readdirSync(resolve(distDir, 'flyin')).sort(), [
    'flyin.css',
    'flyin.umd.js'
]);
