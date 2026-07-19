<?php

namespace App\Actions\Contracts;

use App\Enums\ContractStatus;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScrapContract
{
    /**
     * Scrap (melt) a foreclosed gold item: the contract closes without ever
     * entering the store inventory.
     */
    public function __invoke(Contract $contract): void
    {
        if (! $contract->isActive()) {
            throw ValidationException::withMessages([
                'contracts' => "El contrato {$contract->id} no está activo.",
            ]);
        }

        DB::transaction(function () use ($contract) {
            $contract->update([
                'status' => ContractStatus::Scrapped,
                'ended_at' => now(),
                'settled_amount' => 0,
            ]);

            $contract->repossession()->delete();
        });
    }
}
