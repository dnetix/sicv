<?php

namespace App\Models\Reports\Actions;

use App\Models\Core\Commander\Command;

class RetrievePreSelloutStatisticsCommand extends Command
{
    public $contracts;

    public function __construct($contracts)
    {
        $this->contracts = $contracts;
    }
}
