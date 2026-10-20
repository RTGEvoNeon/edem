<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Категории по видам цветов для посадочных страниц /bukety/{key}.
 * Пока в категории нет доступных товаров, её нет ни в меню, ни в sitemap,
 * а страница не индексируется — тексты лежат в config/seo_texts.php.
 */
return new class extends Migration
{
    /** @var array<string, array{name: string, label_singular: string}> */
    private array $categories = [
        'roses' => ['name' => 'Розы', 'label_singular' => 'букет из роз'],
        'bush-roses' => ['name' => 'Кустовые розы', 'label_singular' => 'букет из кустовых роз'],
        'lilies' => ['name' => 'Лилии', 'label_singular' => 'букет из лилий'],
        'eustoma' => ['name' => 'Эустома', 'label_singular' => 'букет из эустомы'],
        'irises' => ['name' => 'Ирисы', 'label_singular' => 'букет из ирисов'],
        'peonies' => ['name' => 'Пионы', 'label_singular' => 'букет из пионов'],
        'chrysanthemums' => ['name' => 'Хризантемы', 'label_singular' => 'букет из хризантем'],
        'single-flowers' => ['name' => 'Цветы поштучно', 'label_singular' => 'цветы поштучно'],
    ];

    public function up(): void
    {
        $sortOrder = (int) DB::table('categories')->max('sort_order');

        foreach ($this->categories as $key => $data) {
            if (DB::table('categories')->where('key', $key)->exists()) {
                continue;
            }

            DB::table('categories')->insert([
                'key' => $key,
                'name' => $data['name'],
                'label_singular' => $data['label_singular'],
                'sort_order' => ++$sortOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Удаляем только категории, к которым ещё не привязаны товары
        $ids = DB::table('categories')
            ->whereIn('key', array_keys($this->categories))
            ->whereNotIn('id', DB::table('category_product')->select('category_id'))
            ->pluck('id');

        DB::table('categories')->whereIn('id', $ids)->delete();
    }
};
