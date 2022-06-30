<?php

namespace App\Models\Contracts\Actions;

use App\Helpers\RepositoryHelper;
use App\Models\Contracts\Extension;

class CreateNewExtensionAction
{
    private int $contract_id;
    private float $amount;
    private int $user_id;

    public function __construct(int $contract_id, float $amount, int $user_id)
    {
        $this->contract_id = $contract_id;
        $this->amount = $amount;
        $this->user_id = $user_id;
    }

    public function execute(): Extension
    {
        $extension = new Extension([
            'amount' => $this->amount,
            'contract_id' => $this->contract_id,
            'user_id' => $this->user_id,
        ]);
        return RepositoryHelper::forContracts()->storeExtension($extension);
    }
}
