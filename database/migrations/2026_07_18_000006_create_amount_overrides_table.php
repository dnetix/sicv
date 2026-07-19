<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Audit trail for money amounts operators are allowed to override
        // (redemption payoff, foreclosure sale price, POS line price): the
        // operation proceeds, but the difference between the system-computed
        // amount and the entered one stays reviewable by administrators.
        Schema::create('amount_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('operation', 30);
            $table->morphs('auditable');
            $table->unsignedInteger('computed_amount');
            $table->unsignedInteger('entered_amount');
            $table->foreignId('user_id')->constrained();
            $table->dateTime('created_at');

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amount_overrides');
    }
};
