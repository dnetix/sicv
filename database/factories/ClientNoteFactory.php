<?php

namespace Database\Factories;

use App\Enums\ClientNoteSeverity;
use App\Models\Client;
use App\Models\ClientNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClientNote>
 */
class ClientNoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'body' => $this->faker->sentence(),
            'severity' => ClientNoteSeverity::Warning,
            'user_id' => User::factory(),
        ];
    }

    public function alert(): static
    {
        return $this->state(['severity' => ClientNoteSeverity::Alert]);
    }
}
