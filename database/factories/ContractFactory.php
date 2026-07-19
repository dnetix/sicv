<?php

namespace Database\Factories;

use App\Enums\ContractStatus;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ItemType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contract>
 */
class ContractFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'description' => fake()->sentence(6),
            'item_type_id' => fn () => ItemType::ensure(1, 'Sin definir')->id,
            'weight_grams' => null,
            'amount' => fake()->numberBetween(50, 3000) * 1000,
            'monthly_rate' => 10,
            'term_months' => 4,
            'status' => ContractStatus::Active,
            'started_at' => now(),
            'user_id' => User::factory(),
        ];
    }

    public function gold(float $weightGrams = 5.0): static
    {
        return $this->state([
            'item_type_id' => fn () => ItemType::ensure(ItemType::GOLD, 'Oro')->id,
            'weight_grams' => $weightGrams,
        ]);
    }

    public function startedMonthsAgo(int $months): static
    {
        return $this->state(['started_at' => now()->subMonthsNoOverflow($months)]);
    }
}
