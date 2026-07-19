<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_voids', function (Blueprint $table) {
            $table->id();
            $table->text('reason');
            // The legacy system zeroed the contract amount on annulment and
            // only kept it inside the reason text; here it is a real column.
            $table->unsignedInteger('original_amount')->nullable();
            $table->date('voided_at');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        Schema::create('contracts', function (Blueprint $table) {
            // Ids preserved from legacy: they are printed as barcodes on the
            // physical contracts and seals still in circulation.
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->text('description');
            $table->foreignId('item_type_id')->constrained();
            $table->float('weight_grams')->nullable();
            $table->unsignedInteger('amount');
            $table->decimal('monthly_rate', 5, 2);
            $table->unsignedTinyInteger('term_months');
            $table->unsignedTinyInteger('status');
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->unsignedInteger('settled_amount')->nullable();
            $table->foreignId('void_id')->nullable()->constrained('contract_voids');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();

            $table->index(['status', 'started_at']);
            $table->index('ended_at');
        });

        Schema::create('contract_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained();
            $table->unsignedInteger('amount');
            // Payments buy fractional months (payment / monthly interest),
            // stored unrounded exactly as the legacy system did.
            $table->decimal('months', 8, 4);
            $table->dateTime('paid_at');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();

            $table->index('paid_at');
        });

        Schema::create('repossession_queue', function (Blueprint $table) {
            $table->foreignId('contract_id')->primary()->constrained();
            $table->dateTime('queued_at');
            $table->foreignId('user_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repossession_queue');
        Schema::dropIfExists('contract_extensions');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('contract_voids');
    }
};
