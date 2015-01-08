<?php

use Faker\Factory as Faker;
use SICV\Clients\Client;

class ClientsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 100) as $index)
		{
			Client::create([
				'name' => $faker->name,
				'id_type' => 'CC',
				'id_number' => $faker->numberBetween(10000, 999999),
				'id_expedition' => $faker->city,
				'address' => $faker->address,
				'phone_number' => $faker->phoneNumber,
				'email' => $faker->email
			]);
		}
	}

}