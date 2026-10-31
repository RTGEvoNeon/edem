<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\WholesaleProduct;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WholesaleProductsImport implements ToModel, WithCalculatedFormulas, WithHeadingRow
{
    public function model(array $row): ?WholesaleProduct
    {
        $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
        $name = $row['name'] ?? null;

        // Пропускаем пустые строки
        if (empty($name)) {
            return null;
        }

        // Запись по id из файла (БД перед импортом очищена — всегда создаём новую запись)
        $slug = $row['slug'] ?? Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (WholesaleProduct::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $productData = [
            'name' => $name,
            'slug' => $slug,
            'price_tier_1' => (float) ($row['price_tier_1'] ?? 0),
            'price_tier_2' => (float) ($row['price_tier_2'] ?? 0),
            'price_tier_3' => (float) ($row['price_tier_3'] ?? 0),
            'price_tier_4' => (float) ($row['price_tier_4'] ?? 0),
            'price_tier_5' => (float) ($row['price_tier_5'] ?? 0),
            'price_tier_6' => (float) ($row['price_tier_6'] ?? 0),
            'min_quantity' => (int) ($row['min_quantity'] ?? 1000),
            'is_available' => isset($row['is_available']) ? (bool) $row['is_available'] : true,
        ];

        if ($id !== null && $id > 0) {
            $productData['id'] = $id;
        }

        return new WholesaleProduct($productData);
    }
}
