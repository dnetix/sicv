<?php

namespace App\Actions;

use App\Models\AmountOverride;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RecordAmountOverride
{
    /**
     * Persist an audit entry when an operator enters a money amount different
     * from the system-computed one. The operation itself is never blocked —
     * administrators review the differences afterwards.
     */
    public function __invoke(string $operation, Model $auditable, float $computed, int $entered, User $user): void
    {
        $computed = (int) round($computed);

        if ($computed === $entered) {
            return;
        }

        AmountOverride::query()->create([
            'operation' => $operation,
            'auditable_type' => $auditable->getMorphClass(),
            'auditable_id' => $auditable->getKey(),
            'computed_amount' => $computed,
            'entered_amount' => $entered,
            'user_id' => $user->id,
            'created_at' => now(),
        ]);
    }
}
