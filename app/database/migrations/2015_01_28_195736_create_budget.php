<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBudget extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expense_types', function(Blueprint $table){
			$table->increments('id');
			$table->string('name', 80);
		});

		Schema::create('expenses', function(Blueprint $table){
			$table->increments('id');
			$table->integer('amount');
			$table->timestamp('created_at');
			$table->string('description', 180);
			$table->integer('expense_type_id')->unsigned();
			$table->integer('user_id')->unsigned();

			$table->foreign('expense_type_id')->references('id')->on('expense_types');
			$table->foreign('user_id')->references('id')->on('users');
		});


	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('expenses');
		Schema::drop('expense_types');
	}

}
