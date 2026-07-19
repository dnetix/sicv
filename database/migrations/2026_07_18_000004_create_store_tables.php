<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_items', function (Blueprint $table) {
            $table->id();
            // Set when the item entered the store as a foreclosed pawn; its
            // cost is then the contract's loan amount (the legacy system
            // stored 0 here and substituted the loan amount at query time —
            // the import canonicalizes it into `cost`).
            $table->foreignId('contract_id')->nullable()->unique()->constrained();
            $table->text('description');
            $table->foreignId('item_type_id')->constrained();
            $table->date('entered_at');
            $table->unsignedInteger('cost');
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock');
            $table->timestamps();

            $table->index('stock');
        });

        Schema::create('sales', function (Blueprint $table) {
            // Ids preserved from legacy `notacobro`: printed as NC{id} barcodes.
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->date('sold_at');
            $table->unsignedInteger('total');
            $table->unsignedSmallInteger('warranty_days')->default(0);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();

            $table->index('sold_at');
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained();
            $table->foreignId('store_item_id')->constrained();
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('store_items');
    }
};
