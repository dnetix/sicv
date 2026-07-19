<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\ContractExtension;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContractExtension>
 */
class ContractExtensionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contract_id' => Contract::factory(),
            'amount' => 100_000,
            'months' => 1,
            'paid_at' => now(),
            'user_id' => User::factory(),
        ];
    }
}
