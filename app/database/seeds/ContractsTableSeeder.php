<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use SICV\Articles\Article;
use SICV\Contracts\Contract;

class ContractsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 500) as $index)
		{
			Article::create([
				'description' => $faker->sentence(),
				'article_type_id' => $faker->numberBetween(1, 17)
			]);
		}

		foreach(range(1, 200) as $index)
		{
			Contract::create([
				'user_id' => 1,
				'client_id' => $faker->numberBetween(1, 100),
				'months' => $faker->numberBetween(3, 5),
				'amount' => $faker->numberBetween(20000, 5000000),
				'percentage' => 10,
				'state' => 'active',
				'created_at' => $faker->dateTimeBetween('-2 years')
			]);
		}

		foreach(range(1, 200) as $index)
		{
			DB::table('article_contract')->insert(
				[
					'contract_id' => $index,
					'article_id' => $faker->numberBetween(1, 500)
				]
			);
		}
		foreach(range(1, 200) as $index)
		{
			if($faker->numberBetween(0, 1)){
				DB::table('article_contract')->insert(
					[
						'contract_id' => $index,
						'article_id' => $faker->numberBetween(1, 500)
					]
				);
			}
		}
	}

}