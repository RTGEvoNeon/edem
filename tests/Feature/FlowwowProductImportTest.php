<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use App\Services\FlowwowProductImporter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

beforeEach(function (): void {
    Storage::fake('public');
    config(['media-library.disk_name' => 'public']);
    $this->bundle = storage_path('framework/testing/flowwow-'.Str::uuid());
    File::makeDirectory($this->bundle, 0755, true);
    $this->manifest = [
        'version' => 1,
        'source_url' => 'https://flowwow.com/flowers/test-bouquet/',
        'source_name' => 'Чужое название',
        'source_price' => '9900',
        'facts' => ['composition' => 'Не переносить автоматически'],
        'photos' => [
            ['file' => '01.jpg', 'source_url' => 'https://content2.flowwow-images.com/data/flowers/1000x1000/01/first.jpg'],
            ['file' => '02.jpg', 'source_url' => 'https://content2.flowwow-images.com/data/flowers/1000x1000/02/second.jpg'],
        ],
    ];
    foreach (['01.jpg', '02.jpg'] as $name) {
        $image = UploadedFile::fake()->image($name, 50, 50);
        File::copy($image->getPathname(), $this->bundle.'/'.$name);
    }
    File::put($this->bundle.'/manifest.json', json_encode($this->manifest, JSON_THROW_ON_ERROR));
    $this->copy = [
        'name' => 'Розы «Тихий рассвет»',
        'description' => 'Наше описание букета, написанное заново.',
        'price' => 3500,
        'categories' => [Category::firstOrFail()->key],
        'composition' => 'Пятнадцать роз в светлой упаковке.',
    ];
});

afterEach(function (): void {
    File::deleteDirectory($this->bundle);
});

it('imports original copy, our price, categories and ordered local photos as a hidden draft', function (): void {
    $product = app(FlowwowProductImporter::class)->import($this->bundle, $this->copy);

    expect($product->name)->toBe($this->copy['name'])
        ->and($product->description)->toBe($this->copy['description'])
        ->and($product->price)->toBe(3500.0)
        ->and($product->composition)->toBe($this->copy['composition'])
        ->and($product->is_available)->toBeFalse()
        ->and($product->source_url)->toBe($this->manifest['source_url'])
        ->and($product->categories()->pluck('key')->all())->toBe($this->copy['categories']);
    $media = $product->getMedia(Product::PHOTOS_COLLECTION);
    expect($media)->toHaveCount(2)
        ->and($media[0]->file_name)->toBe($product->slug.'-1.jpg')
        ->and($media[1]->file_name)->toBe($product->slug.'-2.jpg')
        ->and($media[0]->getCustomProperty('source_url'))->toBe($this->manifest['photos'][0]['source_url']);
    Storage::disk('public')->assertExists($media[0]->getPathRelativeToRoot());
    expect(is_file($this->bundle.'/01.jpg'))->toBeTrue();
    $this->get('/product/'.$product->slug)->assertNotFound();
});

it('prevents duplicate source imports without changing the existing product', function (): void {
    $importer = app(FlowwowProductImporter::class);
    $product = $importer->import($this->bundle, $this->copy);
    expect(fn () => $importer->import($this->bundle, [...$this->copy, 'name' => 'Другое имя']))
        ->toThrow(RuntimeException::class, 'уже импортирована');
    expect(Product::where('source_url', $product->source_url)->count())->toBe(1)
        ->and($product->fresh()->name)->toBe($this->copy['name']);
});

it('requires original copy and valid shop categories before importing', function (): void {
    $importer = app(FlowwowProductImporter::class);
    expect(fn () => $importer->import($this->bundle, [...$this->copy, 'name' => 'Чужое название']))
        ->toThrow(RuntimeException::class, 'собственное название');
    expect(fn () => $importer->import($this->bundle, [...$this->copy, 'description' => '']))
        ->toThrow(ValidationException::class);
    expect(fn () => $importer->import($this->bundle, [...$this->copy, 'categories' => ['missing-category']]))
        ->toThrow(ValidationException::class);
    expect(Product::where('source_url', $this->manifest['source_url'])->exists())->toBeFalse();
});

it('rejects missing or disguised images before writing to the database', function (): void {
    File::put($this->bundle.'/02.jpg', '<html>Forbidden</html>');
    expect(fn () => app(FlowwowProductImporter::class)->import($this->bundle, $this->copy))
        ->toThrow(RuntimeException::class, 'Некорректное фото');
    expect(Product::where('source_url', $this->manifest['source_url'])->exists())->toBeFalse()
        ->and(Storage::disk('public')->allFiles())->toBe([]);
});

it('rejects file traversal in the manifest', function (): void {
    $this->manifest['photos'][0]['file'] = '../01.jpg';
    File::put($this->bundle.'/manifest.json', json_encode($this->manifest, JSON_THROW_ON_ERROR));
    expect(fn () => app(FlowwowProductImporter::class)->import($this->bundle, $this->copy))
        ->toThrow(ValidationException::class);
});

it('removes saved photos and rolls back the product if an attachment fails', function (): void {
    $created = 0;
    Event::listen('eloquent.creating: '.Media::class, function () use (&$created): void {
        if (++$created === 2) {
            throw new RuntimeException('Attachment failed');
        }
    });
    expect(fn () => app(FlowwowProductImporter::class)->import($this->bundle, $this->copy))
        ->toThrow(RuntimeException::class, 'Attachment failed');
    expect(Product::where('source_url', $this->manifest['source_url'])->exists())->toBeFalse()
        ->and(Storage::disk('public')->allFiles())->toBe([])
        ->and(Media::count())->toBe(0);
});

it('imports through artisan and publishes only with the explicit flag, resolving a slug collision', function (): void {
    $slug = Str::slug($this->copy['name']);
    Product::factory()->create(['slug' => $slug]);
    File::put($this->bundle.'/content.json', json_encode($this->copy, JSON_THROW_ON_ERROR));
    $this->artisan('products:import-flowwow', [
        'bundle' => $this->bundle, '--content' => $this->bundle.'/content.json', '--publish' => true,
    ])->assertSuccessful()->expectsOutputToContain('Фото: 2');
    $product = Product::where('source_url', $this->manifest['source_url'])->firstOrFail();
    expect($product->is_available)->toBeTrue()->and($product->slug)->toBe($slug.'-2');
});
