<?php

namespace Database\Factories\Clients;

use App\Models\Clients\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'id_number' => $this->faker->numberBetween(10000, 1000000000),
            'id_type' => 'CC',
            'id_expedition' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'cell_number' => $this->faker->phoneNumber(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'city' => 'Medellín',
            'flagged' => false,
        ];
    }

    public function flagged()
    {
        return $this->state(function (array $attributes) {
            return [
                'flagged' => true,
            ];
        });
    }
}
