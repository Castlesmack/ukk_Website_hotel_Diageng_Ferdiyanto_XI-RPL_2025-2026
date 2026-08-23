import { cp, mkdir, rm, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';

const root = resolve(import.meta.dirname, '..');
const source = resolve(root, 'static-site-src');
const output = resolve(root, 'static-site');

await rm(output, { recursive: true, force: true });
await mkdir(output, { recursive: true });
await cp(source, output, { recursive: true });

const publicAssets = [
    ['public/logo.png', 'static-site/assets/logo.png'],
    ['public/favicon.png', 'static-site/assets/favicon.png'],
    ['public/uploads', 'static-site/assets/uploads'],
];

for (const [from, to] of publicAssets) {
    await mkdir(dirname(resolve(root, to)), { recursive: true });
    await cp(resolve(root, from), resolve(root, to), { recursive: true });
}

await writeFile(resolve(output, '.nojekyll'), '');
console.log(`Static GitHub Pages site created at ${output}`);
