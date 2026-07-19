<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_types', function (Blueprint $table) {
            // Ids preserved from the legacy `tipoarticulo` table; id 2 (Oro)
            // drives the gold-specific rules (mandatory weight, scrapping).
            $table->id();
            $table->string('name', 45);
            $table->timestamps();
        });

        Schema::create('expense_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->timestamps();
        });

        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name', 120);
            $table->string('tax_id', 60);
            $table->string('name', 120);
            $table->string('address', 120);
            $table->string('phone', 40);
            $table->string('city', 80);
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
        Schema::dropIfExists('expense_types');
        Schema::dropIfExists('item_types');
    }
};
