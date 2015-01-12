<?php  namespace SICV\Contracts\Actions;

use SICV\Contracts\ContractRepository;
use SICV\Contracts\ContractStates;
use SICV\Core\Commander\CommandHandler;

class TerminateContractCommandHandler implements CommandHandler {

    private $contractRepository;

    function __construct(ContractRepository $contractRepository) {
        $this->contractRepository = $contractRepository;
    }

    public function handle($command) {
        $contract = $this->getContractById($command);
        $this->setContractValuesFromCommand($contract, $command);
        $this->updateContract($contract);

        return $contract;
    }

    public function getContractById($command) {
        return $this->contractRepository->getContractById($command->contract_id);
    }

    private function setContractValuesFromCommand(&$contract, $command) {
        $contract->state = ContractStates::TERMINATED;
        $contract->fill((array) $command);
    }

    public function updateContract(&$contract) {
        return $this->contractRepository->update($contract);
    }

}