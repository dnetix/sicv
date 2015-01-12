<?php  namespace SICV\Contracts\Actions;

use SICV\Core\Commander\Command;
use SICV\Utils\Dates\DateHelper;

class TerminateContractCommand extends Command {

    public $contract_id;
    public $end_date;
    public $end_amount;

    function __construct() {
        $this->end_date = DateHelper::create()->toSQLTimestamp();
    }


    public function setCommandValues($contract_id, $end_amount){
        $this->contract_id = $contract_id;
        $this->end_amount = $this->normalizeAmount($end_amount);
    }

}