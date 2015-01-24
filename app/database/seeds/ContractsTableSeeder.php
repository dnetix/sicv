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
			$articleType = $faker->numberBetween(1, 17);
			if($articleType == 2){
				$weight = $faker->randomFloat(2, 0.3, 20);
			}else{
				$weight = null;
			}
			Article::create([
				'description' => $faker->sentence(),
				'article_type_id' => $articleType,
				'weight' => $weight
			]);
		}

		foreach(range(1, 200) as $index)
		{
			$contract = Contract::create([
				'user_id' => 1,
				'client_id' => $faker->numberBetween(1, 100),
				'months' => $faker->numberBetween(3, 5),
				'amount' => $faker->numberBetween(20000, 5000000),
				'percentage' => 10,
				'state' => 'active',
				'created_at' => $faker->dateTimeBetween('-2 years')
			]);

			if($faker->numberBetween(0, 1)){
				DB::table('article_contract')->insert(
					[
						'contract_id' => $contract->id(),
						'article_id' => $faker->numberBetween(1, 500),
						'article_amount' => $contract->amount() / 2
					]
				);
				DB::table('article_contract')->insert(
					[
						'contract_id' => $contract->id(),
						'article_id' => $faker->numberBetween(1, 500),
						'article_amount' => $contract->amount() / 2
					]
				);
			}else{
				DB::table('article_contract')->insert(
					[
						'contract_id' => $contract->id(),
						'article_id' => $faker->numberBetween(1, 500),
						'article_amount' => $contract->amount()
					]
				);
			}
		}

	}

}