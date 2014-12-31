<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use SICV\Users\User;

class UsersTableSeeder extends Seeder {

	public function run()
	{

		User::create([
			'username' => 'admin',
			'password' => 'admin',
			'name' => 'Soporte Diego Calle',
			'email' => 'dnetix@gmail.com',
			'role' => '100',
			'active' => '1'
		]);
//		$faker = Faker::create();
//
//		foreach(range(1, 10) as $index)
//		{
//			User::create([
//
//			]);
//		}
	}

}