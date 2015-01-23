<?php

use SICV\Contracts\Actions\ToggleContractPreSelloutCommand;
use SICV\Contracts\ContractRepository;
use SICV\Core\Commander\CommandBus;

class SelloutController extends BaseController {

    /**
     * @var ContractRepository
     */
    private $contractRepository;

    function __construct(ContractRepository $contractRepository, CommandBus $commandBus) {
        $this->contractRepository = $contractRepository;
        parent::__construct($commandBus);
    }

    public function presellout($contract_id = null){
        if(is_null($contract_id)){
            $contract_id = Input::get('contract_id');
        }

        $command = new ToggleContractPreSelloutCommand($contract_id);
        $result = $this->execute($command);

        return $result;
    }

}
