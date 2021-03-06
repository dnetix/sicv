<?php

class DatabaseSeeder extends Seeder {

	protected $tables = [
		'users', 'article_types', 'expense_types'
	];
	/*protected $tables = [
		'users', 'article_types', 'clients', 'article_contract', 'articles', 'contracts'
	];*/

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		$this->cleanDatabase();

		//$this->call('UsersTableSeeder');
		$this->call('ArticleTypesTableSeeder');
		//$this->call('ClientsTableSeeder');
		//$this->call('ContractsTableSeeder');
		$this->call('ExpenseTypesTableSeeder');
	}

	public function cleanDatabase(){
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		foreach($this->tables as $table){
			DB::table($table)->truncate();
		}
		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
