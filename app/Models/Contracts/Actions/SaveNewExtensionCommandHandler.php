<?php

namespace App\Models\Contracts\Actions;

use App\Models\Contracts\ContractRepository;
use App\Models\Contracts\Extension;
use App\Models\Core\Commander\CommandHandler;

class SaveNewExtensionCommandHandler implements CommandHandler
{
    /**
     * @var ContractRepository
     */
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function handle($command)
    {
        $extension = new Extension();
        $this->fillExtensionFields($extension, $command);

        $contract = $this->contractRepository->getContractById($command->contract_id);
        $this->contractRepository->saveExtension($extension);

        return $extension;
    }

    private function fillExtensionFields(&$extension, $command)
    {
        $extension->fill((array)$command);
    }
}
