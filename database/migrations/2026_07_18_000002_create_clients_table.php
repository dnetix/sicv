<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            // The national id number was the primary key in the legacy system
            // and is printed on contracts; it stays unique and immutable.
            $table->string('document_number', 25)->unique();
            $table->string('document_type', 5);
            $table->string('name', 120);
            $table->string('document_issue_place', 45)->nullable();
            $table->string('address', 80)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('email', 80)->nullable();
            $table->string('city', 45)->nullable();
            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
