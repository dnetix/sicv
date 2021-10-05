<?php

namespace App\Models\Contracts\Actions;

use App\Models\Core\Commander\Command;

class SaveNewExtensionCommand extends Command
{
    public $user_id;
    public $contract_id;
    public $amount;

    private $fields = [
        'user_id',
        'contract_id',
        'amount',
    ];

    public function mapInputData($user_id, $contract_id, $amount)
    {
        $this->user_id = $user_id;
        $this->contract_id = $contract_id;
        $this->amount = $this->normalizeAmount($amount);
    }
}
