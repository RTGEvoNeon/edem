<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'in_progress', 'delivered', 'cancelled'];

        return [
            'customer_name' => fake('ru_RU')->name(),
            'customer_phone' => fake('ru_RU')->phoneNumber(),
            'customer_email' => fake()->safeEmail(),
            'delivery_address' => fake('ru_RU')->address(),
            'delivery_date' => fake()->dateTimeBetween('tomorrow', '+1 month'),
            'notes' => fake()->boolean(30) ? fake('ru_RU')->sentence() : null,
            'total_amount' => fake()->randomFloat(2, 500, 20000),
            'status' => fake()->randomElement($statuses),
        ];
    }

    /**
     * Состояние: новый заказ (pending)
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'delivery_date' => fake()->dateTimeBetween('tomorrow', '+2 weeks'),
        ]);
    }

    /**
     * Состояние: подтвержденный заказ
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    /**
     * Состояние: доставленный заказ
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'delivery_date' => fake()->dateTimeBetween('-1 month', 'yesterday'),
        ]);
    }

    /**
     * Состояние: отмененный заказ
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Состояние: срочный заказ (доставка завтра)
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'delivery_date' => fake()->dateTimeBetween('tomorrow', '+2 days'),
            'notes' => 'Срочный заказ!',
        ]);
    }

    /**
     * Состояние: большой заказ
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_amount' => fake()->randomFloat(2, 10000, 50000),
        ]);
    }
}
