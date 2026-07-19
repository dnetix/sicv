<?php

namespace App\Actions\Contracts;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QueueContracts
{
    /**
     * Mark expired contracts for repossession ("pre-saca"). Contracts
     * already queued are skipped (the legacy app aborted the whole batch
     * on a duplicate).
     *
     * @param  array<int, int>  $contractIds
     * @return int number of contracts queued
     */
    public function __invoke(array $contractIds, User $user): int
    {
        return DB::transaction(function () use ($contractIds, $user) {
            $queued = 0;

            $contracts = Contract::query()
                ->active()
                ->notQueued()
                ->whereIn('id', $contractIds)
                ->get();

            foreach ($contracts as $contract) {
                $contract->repossession()->create([
                    'queued_at' => now(),
                    'user_id' => $user->id,
                ]);

                $queued++;
            }

            return $queued;
        });
    }
}
