<?php

namespace App\Models\Contracts\Actions;

use App\Models\Core\Commander\Command;
use App\Helpers\Dates\DateHelper;

class TerminateContractCommand extends Command
{
    public $contract_id;
    public $end_date;
    public $end_amount;

    public function __construct()
    {
        $this->end_date = DateHelper::create()->toSQLTimestamp();
    }

    public function setCommandValues($contract_id, $end_amount)
    {
        $this->contract_id = $contract_id;
        $this->end_amount = $this->normalizeAmount($end_amount);
    }
}
