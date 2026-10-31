<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WholesaleProductsTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * Возвращает данные для экспорта (пример строк)
     */
    public function array(): array
    {
        return [
            [
                '',
                'Тюльпаны Outlook',
                'outlook',
                59.00,
                58.00,
                57.00,
                56.00,
                55.00,
                54.00,
                1000,
                1,
            ],
            [
                '',
                'Тюльпаны Presto',
                'presto',
                59.00,
                58.00,
                57.00,
                56.00,
                55.00,
                54.00,
                1000,
                1,
            ],
        ];
    }

    /**
     * Заголовки таблицы
     */
    public function headings(): array
    {
        return [
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
    }

    /**
     * Стили для таблицы
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];
    }
}
