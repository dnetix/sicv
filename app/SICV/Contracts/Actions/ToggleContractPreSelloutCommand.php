<?php  namespace SICV\Contracts\Actions;

use SICV\Core\Commander\Command;

class ToggleContractPreSelloutCommand extends Command {

    public $contract_id;

    function __construct($contract_id) {
        $this->contract_id = $contract_id;
    }

}