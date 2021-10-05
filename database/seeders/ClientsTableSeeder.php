<?php

namespace Database\Seeders;

use App\Models\Clients\Client;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    public function run()
    {
        if ($this->command->ask('Seed mock clients Y/n', 'n') === 'Y') {
            $faker = Faker::create();

            $this->command->info('Seeding 100 mock clients');

            foreach (range(1, 100) as $index) {
                Client::create([
                    'name' => $faker->name,
                    'id_type' => 'CC',
                    'id_number' => $faker->numberBetween(10000, 999999999999),
                    'id_expedition' => $faker->city,
                    'address' => $faker->address,
                    'phone_number' => $faker->phoneNumber,
                    'cell_number' => $faker->phoneNumber,
                    'email' => $faker->email,
                ]);
            }
        }
    }
}
