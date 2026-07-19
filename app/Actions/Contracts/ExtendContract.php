<?php

namespace App\Actions\Contracts;

use App\Models\Contract;
use App\Models\ContractExtension;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExtendContract
{
    /**
     * Record an extension payment (abono). The payment buys months of
     * validity: amount ÷ monthly interest, fractional and unrounded —
     * exactly as the legacy system computed it.
     */
    public function __invoke(Contract $contract, int $amount, User $user): ContractExtension
    {
        if (! $contract->isActive()) {
            throw ValidationException::withMessages([
                'amount' => 'No se puede abonar a un contrato inactivo.',
            ]);
        }

        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'El valor del abono debe ser mayor que cero.',
            ]);
        }

        $monthlyInterest = $contract->monthlyInterest();

        // The legacy app divided by zero here on tiny contracts; reject
        // explicitly instead.
        if ($monthlyInterest <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'El contrato no tiene un valor de abono mensual válido.',
            ]);
        }

        return DB::transaction(fn () => $contract->extensions()->create([
            'amount' => $amount,
            'months' => $amount / $monthlyInterest,
            'paid_at' => now(),
            'user_id' => $user->id,
        ]));
    }
}
