import test from 'node:test';
import assert from 'node:assert/strict';
import { extractProduct, productUrl, imageUrl } from '../../scripts/lib/flowwow.mjs';

const source = 'https://flowwow.com/flowers/test-bouquet/';
const first = 'https://content2.flowwow-images.com/data/flowers/1000x1000/01/first.jpg';
const second = 'https://content2.flowwow-images.com/data/flowers/1000x1000/02/second.jpg';
const schema = [
    { '@graph': [
        { '@type': 'Organization', image: 'https://example.com/logo.png' },
        { '@type': 'Product', name: 'Source title', description: 'Do not copy this!',
            image: first.replace('1000x1000', '524x524'),
            offers: { url: source, price: '3900', priceCurrency: 'RUB' } },
        { '@type': 'Product', name: 'Recommendation', url: 'https://flowwow.com/flowers/other/', image: second },
    ] },
    [{ '@type': 'ImageObject', url: source, contentUrl: first },
        { '@type': 'ImageObject', url: source, contentUrl: second },
        { '@type': 'ImageObject', url: 'https://flowwow.com/flowers/other/', contentUrl: first.replace('first', 'unrelated') }],
];

test('extracts only this product gallery, deduplicates sizes, and omits source prose', () => {
    const result = extractProduct(schema, source, { 'Состав': 'Роза — 15 шт.', 'Размер': '40 см' });
    assert.deepEqual(result.image_urls, [first, second]);
    assert.equal(result.source_name, 'Source title');
    assert.equal(result.source_price, '3900');
    assert.deepEqual(result.facts, { composition: 'Роза — 15 шт.', size: '40 см' });
    assert.equal(JSON.stringify(result).includes('Do not copy'), false);
});

test('normalizes tracking and rejects arbitrary hosts, credentials, ports and non-product pages', () => {
    assert.equal(productUrl(source + '?utm_source=test#photo'), source);
    assert.equal(productUrl(source.slice(0, -1)), source);
    assert.equal(productUrl(source.replace('flowwow.com', 'www.flowwow.com')), source);
    for (const url of ['http://flowwow.com/flowers/test/', 'https://flowwow.com.evil.test/flowers/test/',
        'https://127.0.0.1/flowers/test/', 'https://user@flowwow.com/flowers/test/',
        'https://flowwow.com:8443/flowers/test/', 'https://flowwow.com/bryansk/']) {
        assert.throws(() => productUrl(url));
    }
    for (const url of ['https://content2.flowwow-images.com.evil.test/data/flowers/test.jpg',
        'https://flowwow.com/data/flowers/test.jpg', 'https://content2.flowwow-images.com/logo.png']) {
        assert.throws(() => imageUrl(url));
    }
});

test('stops on missing product or unexpected photo host', () => {
    assert.throws(() => extractProduct([], source), /Карточка/);
    assert.throws(() => extractProduct([{ '@type': 'Product', url: source, image: 'https://127.0.0.1/photo.jpg' }], source), /CDN/);
});
