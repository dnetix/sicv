<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_type_id')->constrained();
            $table->unsignedInteger('amount');
            $table->text('description')->nullable();
            $table->dateTime('spent_at');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();

            $table->index('spent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
