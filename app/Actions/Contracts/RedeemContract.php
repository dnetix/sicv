<?php

namespace App\Actions\Contracts;

use App\Actions\RecordAmountOverride;
use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RedeemContract
{
    public function __construct(private readonly RecordAmountOverride $recordOverride) {}

    /**
     * The client pays and takes the item back. The collected amount is
     * suggested by the system but negotiable by the operator; differences
     * are audited.
     */
    public function __invoke(Contract $contract, int $collectedAmount, User $user): void
    {
        if (! $contract->isActive()) {
            throw ValidationException::withMessages([
                'amount' => 'Solo se puede cancelar un contrato activo.',
            ]);
        }

        DB::transaction(function () use ($contract, $collectedAmount, $user) {
            ($this->recordOverride)('redeem', $contract, $contract->payoffAmount(), $collectedAmount, $user);

            $contract->update([
                'status' => ContractStatus::Redeemed,
                'ended_at' => now(),
                'settled_amount' => $collectedAmount,
            ]);
        });
    }
}
