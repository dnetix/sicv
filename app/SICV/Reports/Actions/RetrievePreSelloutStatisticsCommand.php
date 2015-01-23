<?php  namespace SICV\Reports\Actions;

use SICV\Core\Commander\Command;

class RetrievePreSelloutStatisticsCommand extends Command {

    public $contracts;

    function __construct($contracts) {
        $this->contracts = $contracts;
    }

}