import { chromium } from 'playwright';
import { lstat, mkdir, mkdtemp, rename, rm, writeFile } from 'node:fs/promises';
import { resolve, join, dirname } from 'node:path';
import { parseArgs } from 'node:util';
import { extractProduct, productUrl, imageUrl } from './lib/flowwow.mjs';

const { values, positionals } = parseArgs({
    allowPositionals: true,
    options: { output: { type: 'string' }, headless: { type: 'boolean', default: false } },
});

let browser;
let staging;
try {
    if (positionals.length !== 1 || !values.output) {
        throw new Error('Использование: npm run flowwow:fetch -- URL --output storage/app/private/imports/имя');
    }
    const source = productUrl(positionals[0]);
    const output = resolve(values.output);
    const exists = await lstat(output).catch(error => {
        if (error.code !== 'ENOENT') throw error;
        return null;
    });
    if (exists) throw new Error('Папка назначения уже существует. Укажите новую папку.');
    await mkdir(dirname(output), { recursive: true });
    staging = await mkdtemp(join(dirname(output), '.flowwow-'));
    // A normal visible browser works where direct HTTP / headless requests may be blocked.
    browser = await chromium.launch({ headless: values.headless });
    const context = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
    const page = await context.newPage();
    const response = await page.goto(source, { waitUntil: 'domcontentloaded', timeout: 45_000 });
    if (!response?.ok()) throw new Error(`Flowwow вернул HTTP ${response?.status()}. Импорт остановлен.`);
    if (productUrl(page.url()) !== source) throw new Error('Flowwow перенаправил на другую карточку.');
    await page.locator('h1').waitFor({ timeout: 15_000 });
    const data = await page.evaluate(() => ({
        jsonLd: [...document.querySelectorAll('script[type="application/ld+json"]')].flatMap(script => {
            try { return [JSON.parse(script.textContent)]; } catch { return []; }
        }),
        properties: Object.fromEntries([...document.querySelectorAll('.product-card-content .property-item')]
            .map(item => [item.querySelector('h3')?.textContent.trim(),
                [...item.children].filter(child => child.tagName !== 'H3')
                    .map(child => child.textContent.replace(/[\t ]+/g, ' ').replace(/\n\s*\n/g, '\n').trim()).join('\n')])
            .filter(([label]) => ['Состав', 'Размер'].includes(label))),
    }));
    const manifest = extractProduct(data.jsonLd, source, data.properties);
    manifest.photos = [];
    for (const [index, url] of manifest.image_urls.entries()) {
        const photo = await context.request.get(imageUrl(url), { maxRedirects: 0, timeout: 30_000 });
        if (!photo.ok()) throw new Error(`Фото ${index + 1}: HTTP ${photo.status()}.`);
        const mime = photo.headers()['content-type']?.split(';')[0];
        const extension = { 'image/jpeg': 'jpg', 'image/png': 'png', 'image/webp': 'webp' }[mime];
        const body = await photo.body();
        if (!extension || body.length === 0 || body.length > 10 * 1024 * 1024) {
            throw new Error(`Фото ${index + 1}: неподдерживаемый формат или размер больше 10 МБ.`);
        }
        const file = `${String(index + 1).padStart(2, '0')}.${extension}`;
        await writeFile(join(staging, file), body);
        manifest.photos.push({ file, source_url: url });
        await photo.dispose();
    }
    delete manifest.image_urls;
    await writeFile(join(staging, 'manifest.json'), JSON.stringify(manifest, null, 2) + '\n');
    await writeFile(join(staging, 'content.example.json'), JSON.stringify({
        name: '', description: '', price: null, categories: [], composition: '', size: '',
    }, null, 2) + '\n');
    // Existing bundles are never overwritten; failures leave no partial bundle.
    await rename(staging, output);
    staging = undefined;
    console.log(JSON.stringify({ bundle: output, ...manifest }, null, 2));
} catch (error) {
    console.error(error.message);
    process.exitCode = 1;
} finally {
    if (staging) await rm(staging, { recursive: true, force: true });
    if (browser) await browser.close();
}
