<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WholesaleProduct;

class WholesalePriceCalculator
{
    /**
     * Рассчитать итоговую стоимость заказа
     */
    public function calculateTotal(WholesaleProduct $product, int $quantity): float
    {
        $this->validateQuantity($product, $quantity);

        $pricePerUnit = $product->getPriceForQuantity($quantity);

        return $pricePerUnit * $quantity;
    }

    /**
     * Получить цену за единицу для указанного количества
     */
    public function getPricePerUnit(WholesaleProduct $product, int $quantity): float
    {
        return $product->getPriceForQuantity($quantity);
    }

    /**
     * Валидация минимального количества
     */
    public function validateQuantity(WholesaleProduct $product, int $quantity): void
    {
        if ($quantity < $product->min_quantity) {
            throw new \InvalidArgumentException(
                "Минимальное количество для заказа: {$product->min_quantity} шт. Указано: {$quantity} шт."
            );
        }
    }

    /**
     * Проверить, достаточно ли количество
     */
    public function isValidQuantity(WholesaleProduct $product, int $quantity): bool
    {
        return $quantity >= $product->min_quantity;
    }

    /**
     * Получить детали расчета для отображения
     */
    public function getCalculationDetails(WholesaleProduct $product, int $quantity): array
    {
        $pricePerUnit = $product->getPriceForQuantity($quantity);
        $total = $this->calculateTotal($product, $quantity);

        // Определяем текущий ценовой уровень
        $tier = $this->getTierForQuantity($product, $quantity);

        return [
            'product_name' => $product->name,
            'quantity' => $quantity,
            'price_per_unit' => $pricePerUnit,
            'total' => $total,
            'tier' => $tier,
            'min_quantity' => $product->min_quantity,
            'formatted_total' => number_format($total, 2, ',', ' '),
            'formatted_price' => number_format($pricePerUnit, 2, ',', ' '),
        ];
    }

    /**
     * Определить номер ценового уровня для количества
     */
    private function getTierForQuantity(WholesaleProduct $product, int $quantity): int
    {
        $level = 1;

        foreach ($product->getTierInfo() as $index => $tier) {
            if ($quantity >= $tier['min_quantity']) {
                $level = $index + 1;
            }
        }

        return $level;
    }
}
