<?php

namespace App\Actions\Contracts;

use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Models\ContractVoid;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoidContract
{
    /**
     * Annul a contract (wrong data, legal problem, …). As in the legacy
     * system the contract amount is zeroed so money reports ignore it, but
     * the original amount is now kept in a dedicated column instead of
     * being buried inside the reason text.
     */
    public function __invoke(Contract $contract, string $reason, User $user): void
    {
        if (! $contract->isActive()) {
            throw ValidationException::withMessages([
                'reason' => 'Solo se puede anular un contrato activo.',
            ]);
        }

        DB::transaction(function () use ($contract, $reason, $user) {
            $void = ContractVoid::query()->create([
                'reason' => $reason,
                'original_amount' => $contract->amount,
                'voided_at' => today(),
                'user_id' => $user->id,
            ]);

            $contract->update([
                'status' => ContractStatus::Voided,
                'ended_at' => now(),
                'amount' => 0,
                'void_id' => $void->id,
            ]);
        });
    }
}
