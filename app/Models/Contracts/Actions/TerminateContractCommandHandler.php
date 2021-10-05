<?php

namespace App\Models\Contracts\Actions;

use App\Models\Contracts\ContractRepository;
use App\Models\Contracts\ContractStates;
use App\Models\Core\Commander\CommandHandler;

class TerminateContractCommandHandler implements CommandHandler
{
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function handle($command)
    {
        $contract = $this->getContractById($command);
        $this->setContractValuesFromCommand($contract, $command);
        $this->updateContract($contract);

        return $contract;
    }

    public function getContractById($command)
    {
        return $this->contractRepository->getContractById($command->contract_id);
    }

    private function setContractValuesFromCommand(&$contract, $command)
    {
        $contract->state = ContractStates::TERMINATED;
        $contract->fill((array)$command);
    }

    public function updateContract(&$contract)
    {
        return $this->contractRepository->update($contract);
    }
}
