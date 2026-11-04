const pageHosts = new Set(['flowwow.com', 'www.flowwow.com']);

export function productUrl(value) {
    const url = new URL(value);
    if (url.protocol !== 'https:' || !pageHosts.has(url.hostname) || url.port || url.username || url.password
        || !/^\/flowers\/[a-z0-9-]+\/?$/.test(url.pathname)) {
        throw new Error('Нужна ссылка https://flowwow.com/flowers/название-товара/');
    }
    return `https://flowwow.com${url.pathname.replace(/\/$/, '')}/`;
}

export function imageUrl(value) {
    const url = new URL(value);
    if (url.protocol !== 'https:' || !/^content\d+\.flowwow-images\.com$/.test(url.hostname)
        || url.port || url.username || url.password || !url.pathname.startsWith('/data/flowers/')) {
        throw new Error('Фото находится вне товарного CDN Flowwow.');
    }
    return url.href;
}

function nodes(data) {
    return data.flatMap(value => Array.isArray(value) ? nodes(value)
        : value && typeof value === 'object' ? [value, ...nodes(value['@graph'] ?? [])] : []);
}

function hasType(node, type) {
    return [node['@type']].flat().some(value => value === type || value === `https://schema.org/${type}`);
}

function belongsTo(value, source) {
    try { return productUrl(value) === source; } catch { return false; }
}

export function extractProduct(jsonLd, requestedUrl, properties = {}) {
    const source = productUrl(requestedUrl);
    const all = nodes(jsonLd);
    const product = all.find(node => hasType(node, 'Product')
        && [node.url, node['@id'], ...[node.offers].flat().map(offer => offer?.url)]
            .some(url => belongsTo(url, source)));
    if (!product) throw new Error('Карточка товара не найдена. Возможно, Flowwow показал блокировку.');

    // Only images explicitly associated with this product; never recommendations or reviews.
    const gallery = all.filter(node => hasType(node, 'ImageObject') && belongsTo(node.url, source))
        .map(node => node.contentUrl);
    const primary = [product.image].flat().filter(Boolean).map(value => typeof value === 'string' ? value : value.url);
    const unique = new Map();
    for (const value of [...gallery, ...primary]) {
        const url = imageUrl(value);
        const key = new URL(url).pathname.replace(/\/\d+x\d+\//, '/size/');
        if (!unique.has(key)) unique.set(key, url);
    }
    if (!unique.size || unique.size > 12) throw new Error('Ожидалось от 1 до 12 фотографий товара.');
    const offers = [product.offers].flat();
    return {
        version: 1,
        source_url: source,
        source_name: product.name,
        source_price: offers[0]?.price ?? null,
        source_currency: offers[0]?.priceCurrency ?? null,
        facts: {
            composition: properties['Состав'] ?? null,
            size: properties['Размер'] ?? null,
        },
        image_urls: [...unique.values()],
    };
}
