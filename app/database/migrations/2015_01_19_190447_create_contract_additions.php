<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractAdditions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('annuls', function(Blueprint $table){
			$table->integer('id');
			$table->dateTime('created_at');
			$table->text('note');
			$table->integer('original_amount');
			$table->integer('contract_id')->unsigned();
			$table->integer('user_id')->unsigned();

			$table->foreign('contract_id')->references('id')->on('contracts');
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
		Schema::drop('annuls');
	}

}
