<?php

namespace Database\Factories;

use App\Models\ItemType;
use App\Models\StoreItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StoreItem>
 */
class StoreItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contract_id' => null,
            'description' => fake()->sentence(4),
            'item_type_id' => fn () => ItemType::ensure(1, 'Sin definir')->id,
            'entered_at' => today(),
            'cost' => fake()->numberBetween(20, 500) * 1000,
            'price' => fake()->numberBetween(50, 900) * 1000,
            'stock' => 1,
        ];
    }

    public function soldOut(): static
    {
        return $this->state(['stock' => 0]);
    }
}
