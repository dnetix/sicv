<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractAdditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_sellouts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->unsigned();

            $table->foreign('contract_id')->references('id')->on('contracts');
        });

        Schema::create('annuls', function (Blueprint $table) {
            $table->integer('id');
            $table->dateTime('created_at');
            $table->text('note');
            $table->integer('original_amount');
            $table->integer('contract_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('sellouts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('note')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->float('gold_weight');
            $table->integer('gold_amount')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('contract_sellout', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sellout_id')->unsigned();
            $table->integer('contract_id')->unsigned();

            $table->foreign('sellout_id')->references('id')->on('sellouts');
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

        Schema::drop('contract_sellout');
        Schema::drop('sellouts');
        Schema::drop('pre_sellouts');
        Schema::drop('annuls');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
