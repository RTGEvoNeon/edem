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
 * @property float $price_tier_4
 * @property float $price_tier_5
 * @property float $price_tier_6
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
        'price_tier_4',
        'price_tier_5',
        'price_tier_6',
        'min_quantity',
        'is_available',
    ];

    protected $casts = [
        'price_tier_1' => 'float',
        'price_tier_2' => 'float',
        'price_tier_3' => 'float',
        'price_tier_4' => 'float',
        'price_tier_5' => 'float',
        'price_tier_6' => 'float',
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
        $price = $this->price_tier_1;

        foreach ($this->getTierInfo() as $tier) {
            if ($quantity >= $tier['min_quantity']) {
                $price = $tier['price'];
            }
        }

        return $price;
    }

    /**
     * Получить информацию о всех ценовых уровнях
     *
     * min_quantity — с какого количества действует цена (включительно).
     * Количества в прайсе кратны 20, поэтому «до 5 000» и «5 020–10 000» не оставляют пробелов.
     *
     * @return array<int, array{min_quantity: int, max_quantity: int|null, price: float, label: string}>
     */
    public function getTierInfo(): array
    {
        return [
            ['min_quantity' => 1, 'max_quantity' => 5000, 'price' => $this->price_tier_1, 'label' => 'до 5 000 шт'],
            ['min_quantity' => 5001, 'max_quantity' => 10000, 'price' => $this->price_tier_2, 'label' => '5 020 – 10 000 шт'],
            ['min_quantity' => 10001, 'max_quantity' => 30000, 'price' => $this->price_tier_3, 'label' => '10 020 – 30 000 шт'],
            ['min_quantity' => 30001, 'max_quantity' => 50000, 'price' => $this->price_tier_4, 'label' => '30 020 – 50 000 шт'],
            ['min_quantity' => 50001, 'max_quantity' => 100000, 'price' => $this->price_tier_5, 'label' => '50 020 – 100 000 шт'],
            ['min_quantity' => 100001, 'max_quantity' => null, 'price' => $this->price_tier_6, 'label' => 'от 100 020 шт'],
        ];
    }

    /**
     * Получить минимальную цену (для последнего уровня)
     */
    public function getMinPriceAttribute(): float
    {
        return $this->price_tier_6;
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
        $images = [];

        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, $imageExtensions)) {
                $images[] = Storage::url($file);
            }
        }

        // Сортируем по имени файла
        sort($images);

        return $images;
    }
}
