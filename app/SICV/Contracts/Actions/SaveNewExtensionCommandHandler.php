<?php  namespace SICV\Contracts\Actions;

use SICV\Core\Commander\CommandHandler;
use SICV\Contracts\Contract;
use SICV\Contracts\ContractRepository;
use SICV\Contracts\ContractStates;
use SICV\Contracts\Extension;

class SaveNewExtensionCommandHandler implements CommandHandler {

    /**
     * @var ContractRepository
     */
    private $contractRepository;

    function __construct(ContractRepository $contractRepository) {
        $this->contractRepository = $contractRepository;
    }

    public function handle($command) {

        $extension = new Extension();
        $this->fillExtensionFields($extension, $command);

        $contract = $this->contractRepository->getContractById($command->contract_id);
        $this->contractRepository->saveExtension($extension);

        return $extension;
    }

    private function fillExtensionFields(&$extension, $command) {
        $extension->fill((array) $command);
    }

}