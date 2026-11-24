<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $opening = fake()->randomFloat(2, 10, 500);

        return [
            'item_code' => 'ITM-'.fake()->unique()->numerify('####'),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(['PCS', 'KG', 'MTR', 'BOX', 'SET']),
            'category' => fake()->randomElement(['Mechanical Parts', 'Electronics & Motors', 'Hydraulics', 'Pneumatics']),
            'opening_stock' => $opening,
            'opening_stock_date' => now()->toDateString(),
            'reorder_level' => 20,
            'cost_price' => fake()->randomFloat(2, 10, 200),
            'unit_price' => fake()->randomFloat(2, 25, 400),
            'current_stock' => $opening,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the item is low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn () => [
            'opening_stock' => 5,
            'current_stock' => 5,
            'reorder_level' => 20,
        ]);
    }

    /**
     * Indicate that the item is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn () => [
            'opening_stock' => 0,
            'current_stock' => 0,
            'reorder_level' => 20,
        ]);
    }
}
