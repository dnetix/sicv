<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_number' => (string) fake()->unique()->numberBetween(10_000_000, 1_999_999_999),
            'document_type' => 'CC',
            'name' => mb_strtoupper(fake()->name()),
            'document_issue_place' => 'La Ceja',
            'address' => fake()->streetAddress(),
            'phone' => fake()->numerify('55#####'),
            'mobile' => fake()->numerify('3#########'),
            'email' => fake()->optional()->safeEmail(),
            'city' => 'La Ceja',
        ];
    }
}
