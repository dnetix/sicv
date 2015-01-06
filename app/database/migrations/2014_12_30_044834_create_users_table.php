<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 80)->unique();
			$table->string('password');
			$table->string('name', 120);
			$table->string('email')->unique();
			$table->string('id_type', 6)->nullable();
			$table->string('id_number', 30)->nullable();
			$table->string('id_expedition', 80)->nullable();
			$table->string('address')->nullable();
			$table->integer('role')->unsigned();
			$table->boolean('active');
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
