<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120)->index();
            $table->string('id_type', 6);
            $table->string('id_number', 30)->unique();
            $table->string('id_expedition', 80);
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->nullable();
            $table->string('profile_image')->nullable();
            $table->boolean('flagged')->default(false);
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
        Schema::drop('clients');
    }
}
