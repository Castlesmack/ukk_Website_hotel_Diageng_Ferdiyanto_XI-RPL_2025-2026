import { access, readFile, readdir } from 'node:fs/promises';
import { resolve } from 'node:path';

const root = resolve(import.meta.dirname, '..');
const output = resolve(root, 'static-site');
const html = await readFile(resolve(output, 'index.html'), 'utf8');
const failures = [];

if (/localhost|127\.0\.0\.1/i.test(html)) failures.push('localhost URL found');
if (/\b(?:\.env(?:\.[^\s"'<>]*)?|vendor|storage)\b/i.test(html)) failures.push('private/server path found');
if (/(?:src|href)=["']\/(?:css|js|images)(?:\/|["'])/i.test(html) || /url\(["']?\/(?:css|js|images)\//i.test(html)) {
    failures.push('root-relative CSS, JS, or image URL found');
}

const references = new Set();
for (const match of html.matchAll(/src=["']([^"']+)["']/gi)) references.add(match[1]);
for (const match of html.matchAll(/href=["']([^"']+)["']/gi)) {
    if (/^(?:assets\/|[^/]+\.[a-z0-9]+(?:[?#].*)?$)/i.test(match[1])) references.add(match[1]);
}
for (const match of html.matchAll(/url\(["']?([^)'"\s]+)["']?\)/gi)) references.add(match[1]);

for (const reference of references) {
    if (/^(?:[a-z]+:|\/\/|#|data:|javascript:)/i.test(reference)) continue;
    const cleanReference = reference.split(/[?#]/, 1)[0];
    if (!cleanReference || cleanReference.includes('${')) continue;
    try {
        await access(resolve(output, cleanReference));
    } catch {
        failures.push(`missing asset: ${reference}`);
    }
}

const files = [];
async function collect(directory) {
    for (const entry of await readdir(directory, { withFileTypes: true })) {
        const path = resolve(directory, entry.name);
        if (entry.isDirectory()) await collect(path);
        else files.push(path);
    }
}
await collect(output);
for (const file of files) {
    const relative = file.slice(output.length + 1);
    if (/^\.env(?:\.|$)|(^|[\\/])(?:vendor|storage)(?:[\\/]|$)|\.php$/i.test(relative)) {
        failures.push(`forbidden artifact file: ${relative}`);
    }
}

if (failures.length) {
    console.error(failures.join('\n'));
    process.exit(1);
}

console.log(`GitHub Pages artifact valid: ${files.length} files, ${references.size} references checked.`);
