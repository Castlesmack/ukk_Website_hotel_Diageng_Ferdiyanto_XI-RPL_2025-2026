import { cp, mkdir, rm, writeFile } from 'node:fs/promises';
import { execFile } from 'node:child_process';
import { promisify } from 'node:util';
import { dirname, resolve } from 'node:path';

const execFileAsync = promisify(execFile);
const root = resolve(import.meta.dirname, '..');
const output = resolve(root, 'static-site');

await rm(output, { recursive: true, force: true });
await mkdir(output, { recursive: true });

const { stdout: renderedPage } = await execFileAsync('php', [
    resolve(root, 'scripts/render-github-pages.php'),
], { cwd: root, maxBuffer: 10 * 1024 * 1024 });
if (!renderedPage.includes('<!doctype html>')) {
    throw new Error('Laravel did not render a valid HTML page for GitHub Pages.');
}
await writeFile(resolve(output, 'index.html'), renderedPage);

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
