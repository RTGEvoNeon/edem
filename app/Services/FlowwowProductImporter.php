<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use Throwable;

class FlowwowProductImporter
{
    /**
     * Photos are downloaded separately by Playwright. This importer never requests external URLs.
     *
     * @param  array<string, mixed>  $content  Original copy and our retail price/category keys.
     */
    public function import(string $bundle, array $content, bool $publish = false): Product
    {
        $directory = realpath($bundle);
        if ($directory === false || ! is_dir($directory)) {
            throw new RuntimeException('Папка импорта не найдена.');
        }
        $manifest = $this->readJson($directory.'/manifest.json');
        Validator::make($manifest, [
            'version' => ['required', 'integer', 'in:1'],
            'source_url' => ['required', 'string', 'max:512', 'regex:~^https://flowwow\.com/flowers/[a-z0-9-]+/$~'],
            'source_name' => ['required', 'string', 'max:1000'],
            'photos' => ['required', 'array', 'min:1', 'max:12'],
            'photos.*.file' => ['required', 'string', 'distinct', 'regex:/^\d{2}\.(jpg|png|webp)$/'],
            'photos.*.source_url' => ['required', 'string', 'max:2048', 'regex:~^https://content\d+\.flowwow-images\.com/data/flowers/[^\s]+$~'],
        ])->validate();
        $data = Validator::make($content, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'string', 'distinct', Rule::exists('categories', 'key')],
            'composition' => ['nullable', 'string', 'max:5000'],
            'size' => ['nullable', 'string', 'max:1000'],
            'care_instructions' => ['nullable', 'string', 'max:5000'],
            'seo_text' => ['nullable', 'string', 'max:10000'],
        ])->validate();
        $data['name'] = trim($data['name']);
        $data['description'] = trim($data['description']);
        if (mb_strtolower($data['name']) === mb_strtolower(trim($manifest['source_name']))) {
            throw new RuntimeException('Напишите собственное название, отличающееся от Flowwow.');
        }
        if (Product::where('source_url', $manifest['source_url'])->exists()) {
            throw new RuntimeException('Эта ссылка уже импортирована. Измените существующий товар в админке.');
        }

        // Validate the complete bundle before creating any records or copying any files.
        foreach ($manifest['photos'] as $photo) {
            $path = realpath($directory.'/'.$photo['file']);
            $size = $path !== false && is_file($path) ? filesize($path) : false;
            $image = $path !== false ? @getimagesize($path) : false;
            if ($path === false || dirname($path) !== $directory || $size === false || $size < 1
                || $size > 10 * 1024 * 1024 || $image === false
                || ! in_array($image['mime'], ['image/jpeg', 'image/png', 'image/webp'], true)
                || $image[0] * $image[1] > 40_000_000) {
                throw new RuntimeException('Некорректное фото: '.$photo['file']);
            }
        }

        $slug = substr(Str::slug($data['name']), 0, 230) ?: 'buket';
        $baseSlug = $slug;
        for ($suffix = 2; Product::where('slug', $slug)->exists(); $suffix++) {
            $slug = $baseSlug.'-'.$suffix;
        }
        $categoryIds = Category::whereIn('key', $data['categories'])->pluck('id');
        $data['category'] = $data['categories'][0];
        unset($data['categories']);
        $product = null;

        return DB::transaction(function () use (&$product, $data, $slug, $manifest, $directory, $categoryIds, $publish): Product {
            try {
                $product = Product::create([
                    ...$data,
                    'slug' => $slug,
                    'source_url' => $manifest['source_url'],
                    'is_available' => false,
                ]);
                $product->categories()->sync($categoryIds);
                foreach ($manifest['photos'] as $index => $photo) {
                    $product->addMedia($directory.'/'.$photo['file'])
                        ->preservingOriginal()
                        ->usingName($data['name'].' — фото '.($index + 1))
                        ->usingFileName($slug.'-'.($index + 1).'.'.pathinfo($photo['file'], PATHINFO_EXTENSION))
                        ->withCustomProperties(['source_url' => $photo['source_url']])
                        ->toMediaCollection(Product::PHOTOS_COLLECTION);
                }
                $product->update(['is_available' => $publish]);

                return $product;
            } catch (Throwable $exception) {
                // Database rollback cannot remove files; delete them before rolling back media rows.
                $product?->deleteAllMedia();
                throw $exception;
            }
        });
    }

    /** @return array<string, mixed> */
    public function readJson(string $path): array
    {
        if (! is_file($path) || filesize($path) > 1_048_576) {
            throw new RuntimeException('JSON-файл не найден или превышает 1 МБ: '.$path);
        }
        $data = json_decode((string) file_get_contents($path), true, 32, JSON_THROW_ON_ERROR);
        if (! is_array($data) || array_is_list($data)) {
            throw new RuntimeException('Ожидался JSON-объект: '.$path);
        }

        return $data;
    }
}
