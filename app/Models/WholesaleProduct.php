<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property float $price_tier_1
 * @property float $price_tier_2
 * @property float $price_tier_3
 * @property int $min_quantity
 * @property bool $is_available
 * @property-read float $min_price
 * @property-read string $main_image
 * @property-read array<int, string> $image_urls
 */
class WholesaleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'price_tier_1',
        'price_tier_2',
        'price_tier_3',
        'min_quantity',
        'is_available',
    ];

    protected $casts = [
        'price_tier_1' => 'float',
        'price_tier_2' => 'float',
        'price_tier_3' => 'float',
        'min_quantity' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * Scope: только доступные товары
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    /**
     * Получить цену для указанного количества
     */
    public function getPriceForQuantity(int $quantity): float
    {
        if ($quantity >= 10000) {
            return $this->price_tier_3;
        }

        if ($quantity >= 5000) {
            return $this->price_tier_2;
        }

        return $this->price_tier_1;
    }

    /**
     * Получить информацию о всех ценовых уровнях
     */
    public function getTierInfo(): array
    {
        return [
            [
                'min_quantity' => 1000,
                'max_quantity' => 4999,
                'price' => $this->price_tier_1,
                'label' => '1000-4999 шт',
            ],
            [
                'min_quantity' => 5000,
                'max_quantity' => 9999,
                'price' => $this->price_tier_2,
                'label' => '5000-9999 шт',
            ],
            [
                'min_quantity' => 10000,
                'max_quantity' => null,
                'price' => $this->price_tier_3,
                'label' => '10000+ шт',
            ],
        ];
    }

    /**
     * Получить минимальную цену (для tier 3)
     */
    public function getMinPriceAttribute(): float
    {
        return $this->price_tier_3;
    }

    /**
     * Accessor: главное изображение или fallback
     */
    protected function mainImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                $filesystemImages = $this->getFilesystemImages();
                if (! empty($filesystemImages)) {
                    return $filesystemImages[0];
                }

                return '/images/placeholder.jpg';
            }
        );
    }

    /**
     * Accessor: все активные изображения как URL
     */
    protected function imageUrls(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getFilesystemImages();
            }
        );
    }

    /**
     * Получить изображения из файловой системы wholesales/{id}/
     */
    private function getFilesystemImages(): array
    {
        $directory = "wholesales/{$this->id}";

        if (! Storage::disk('public')->exists($directory)) {
            return [];
        }

        $files = Storage::disk('public')->files($directory);
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $byName = [];

        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (! in_array($extension, $imageExtensions)) {
                continue;
            }

            // Одно фото может лежать в нескольких форматах (1.jpg и 1.webp) — берём одно, предпочитая webp
            $name = pathinfo($file, PATHINFO_FILENAME);
            if (! isset($byName[$name]) || $extension === 'webp') {
                $byName[$name] = $file;
            }
        }

        // Сортируем по имени файла
        ksort($byName);

        return array_map(fn (string $file) => Storage::url($file), array_values($byName));
    }
}
