<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'role' => UserRole::Employee,
            'active' => true,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function administrator(): static
    {
        return $this->state(['role' => UserRole::Administrator]);
    }

    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }

    /**
     * A user imported from the legacy system who has not logged in yet:
     * no bcrypt password, only the old haval128,4 hash.
     */
    public function legacy(string $plainPassword = 'password'): static
    {
        return $this->state([
            'password' => null,
            'legacy_password_hash' => hash('haval128,4', $plainPassword),
        ]);
    }
}
