<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellsModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buy_price')->nullable();
            $table->integer('sell_price')->nullable();
            $table->integer('article_id')->unsigned();
            $table->integer('contract_id')->nullable()->unsigned();
            $table->integer('quantity')->index();

            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('contract_id')->references('id')->on('contracts');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('amount');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('invoice_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('amount');

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('product_id')->references('id')->on('products');
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

        Schema::drop('invoice_product');
        Schema::drop('invoices');
        Schema::drop('products');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
