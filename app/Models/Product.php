<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $source_url
 * @property float $price
 * @property string $category
 * @property bool $is_available
 * @property-read string $main_image
 * @property-read array<int, string> $image_urls
 * @property-read array<int, string> $image_urls_webp
 * @property-read string $composition
 * @property-read string $size
 * @property-read string $care_instructions
 * @property-read string $seo_text
 * @property-read Collection<int, Category> $categories
 */
class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public const PHOTOS_COLLECTION = 'photos';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'source_url',
        'composition',
        'size',
        'care_instructions',
        'seo_text',
        'price',
        'category',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'float',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::PHOTOS_COLLECTION);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Scope: только доступные товары
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope: с изображениями (для совместимости, изображения загружаются через MediaLibrary)
     */
    public function scopeWithImages(Builder $query): Builder
    {
        return $query->with('media');
    }

    /**
     * Scope: случайная выборка ограниченного количества товаров.
     */
    public function scopeRandomLimit(Builder $query, int $limit): Builder
    {
        return $query->inRandomOrder()->limit($limit);
    }

    /**
     * Accessor: главное изображение или fallback
     */
    protected function mainImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl(self::PHOTOS_COLLECTION) ?: '/images/placeholder.jpg'
        );
    }

    /**
     * Accessor: все изображения как URL
     */
    protected function imageUrls(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getMedia(self::PHOTOS_COLLECTION)
                ->map(fn (Media $media) => $media->getUrl())
                ->all()
        );
    }

    /**
     * Accessor: все изображения в виде WebP-URL (с fallback на оригинал).
     */
    protected function imageUrlsWebp(): Attribute
    {
        return Attribute::make(
            get: fn (): array => $this->getMedia(self::PHOTOS_COLLECTION)
                ->map(fn (Media $media) => self::webpForMedia($media))
                ->all()
        );
    }

    /**
     * Вернуть WebP-версию файла медиа, если она существует на диске.
     * Иначе возвращает исходный URL без изменений.
     */
    public static function webpForMedia(Media $media): string
    {
        $url = $media->getUrl();

        if (! preg_match('/\.(jpe?g|png)$/i', $url)) {
            return $url;
        }

        $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $media->getPathRelativeToRoot());

        if ($webpPath !== null && Storage::disk($media->disk)->exists($webpPath)) {
            return preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
        }

        return $url;
    }

    /**
     * Вернуть WebP-версию для произвольного URL (диск public), если файл существует.
     * Используется компонентом <x-product-image>, где под рукой только URL, а не Media.
     */
    public static function webpForUrl(string $imageUrl): string
    {
        if (! preg_match('/\.(jpe?g|png)$/i', $imageUrl)) {
            return $imageUrl;
        }

        $webpUrl = preg_replace('/\.(jpe?g|png)$/i', '.webp', $imageUrl);
        $relativePath = ltrim(str_replace('/storage/', '', parse_url($webpUrl, PHP_URL_PATH) ?? ''), '/');

        if ($relativePath !== '' && Storage::disk('public')->exists($relativePath)) {
            return $webpUrl;
        }

        return $imageUrl;
    }

    /**
     * Человекочитаемое название категории в единственном числе.
     */
    private function categoryLabel(): string
    {
        /** @var Category|null $category */
        $category = $this->categories->first();

        if ($category instanceof Category) {
            return $category->label_singular ?? mb_strtolower($category->name);
        }

        return 'букет';
    }

    /**
     * Accessor: состав букета.
     * Если поле заполнено вручную — возвращаем его, иначе — текст по шаблону.
     */
    protected function composition(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): string {
                if (filled($value)) {
                    return $value;
                }

                return 'Свежесрезанные цветы высшего качества, сезонная зелень и декоративная '
                    .'упаковка. Точный состав '.$this->categoryLabel().' «'.$this->name.'» '
                    .'наши флористы согласуют с вами при оформлении заказа и могут адаптировать '
                    .'под ваш бюджет и пожелания.';
            }
        );
    }

    /**
     * Accessor: размер букета.
     */
    protected function size(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): string {
                if (filled($value)) {
                    return $value;
                }

                return 'Стандартный размер. Высоту и объём букета можно увеличить — '
                    .'уточните пожелания при заказе.';
            }
        );
    }

    /**
     * Accessor: рекомендации по уходу.
     */
    protected function careInstructions(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): string {
                if (filled($value)) {
                    return $value;
                }

                return 'Подрежьте стебли под углом 45° острым ножом и поставьте букет в чистую '
                    .'прохладную воду. Меняйте воду каждые 1–2 дня, заново подрезая стебли. '
                    .'Держите цветы вдали от прямых солнечных лучей, сквозняков, отопительных '
                    .'приборов и спелых фруктов — так букет дольше сохранит свежесть.';
            }
        );
    }

    /**
     * Accessor: SEO-текст под карточкой товара.
     * Уникальный длинный текст для поисковых систем; при отсутствии генерируется по шаблону.
     */
    protected function seoText(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): string {
                if (filled($value)) {
                    return $value;
                }

                $label = $this->categoryLabel();
                $price = number_format((float) $this->price, 0, '', ' ');

                return "{$label} «{$this->name}» — это композиция из свежих цветов, "
                    .'собранная флористами цветочной мастерской «Эдемский сад» в Брянске. '
                    ."Каждый {$label} мы создаём вручную в день доставки, поэтому цветы "
                    .'приезжают к получателю свежими и стоят максимально долго. '
                    ."Купить {$this->name} в Брянске можно за {$price} ₽ с бесплатной "
                    .'доставкой по городу — курьер привезёт букет в удобное время, в том '
                    .'числе в день заказа. Такой букет станет прекрасным подарком на день '
                    .'рождения, годовщину, 8 Марта или просто знаком внимания без повода. '
                    .'Если хотите изменить состав, размер или добавить открытку — напишите '
                    .'нам, и мы соберём букет специально под вас.';
            }
        );
    }
}
