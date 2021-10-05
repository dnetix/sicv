<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('article_type', 120);
            $table->integer('article_type_id')->nullable()->unsigned();

            $table->foreign('article_type_id')->references('id')->on('article_types');
        });

        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->integer('months');
            $table->float('percentage');
            $table->integer('amount');
            $table->string('state', 10)->index();
            $table->timestamps();
            $table->timestamp('end_date')->nullable();
            $table->integer('end_amount')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('clients');
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 255);
            $table->float('weight')->nullable();
            $table->string('location', 45)->nullable();
            $table->integer('article_type_id')->unsigned();
            $table->timestamps();

            $table->foreign('article_type_id')->references('id')->on('article_types');
        });

        Schema::create('extensions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount');
            $table->integer('contract_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('article_contract', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->unsigned();
            $table->integer('article_id')->unsigned();
            $table->integer('article_amount');

            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('article_id')->references('id')->on('articles');
        });

        Schema::create('client_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('note');
            $table->timestamps();
            $table->integer('client_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('contract_id')->nullable()->unsigned();
            $table->string('importance', 10);

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('contract_id')->references('id')->on('contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::drop('article_types');
        Schema::drop('contracts');
        Schema::drop('articles');
        Schema::drop('extensions');
        Schema::drop('article_contract');
        Schema::drop('client_notes');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
