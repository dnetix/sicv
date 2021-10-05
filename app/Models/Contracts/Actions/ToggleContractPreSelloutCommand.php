<?php

namespace App\Models\Contracts\Actions;

use App\Models\Core\Commander\Command;

class ToggleContractPreSelloutCommand extends Command
{
    public $contract_id;

    public function __construct($contract_id)
    {
        $this->contract_id = $contract_id;
    }
}
