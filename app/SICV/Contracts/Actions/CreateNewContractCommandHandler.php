<?php  namespace SICV\Contracts\Actions;

use SICV\Core\Commander\CommandHandler;
use SICV\Contracts\Contract;
use SICV\Contracts\ContractRepository;
use SICV\Contracts\ContractStates;

class CreateNewContractCommandHandler implements CommandHandler {

    /**
     * @var ContractRepository
     */
    private $contractRepository;

    function __construct(ContractRepository $contractRepository) {
        $this->contractRepository = $contractRepository;
    }

    public function handle($command) {

        $contract = new Contract();
        $this->fillContractFields($contract, $command);
        $this->createNewContract($contract);
        $this->associateContractWithArticles($contract, $command->articles_id);

    }

    private function fillContractFields(Contract &$contract, $command) {
        $contract->fill((array) $command);
        $contract->state = ContractStates::ACTIVE;
    }

    private function createNewContract(&$contract) {
        $this->contractRepository->create($contract);
    }

    private function associateContractWithArticles(&$contract, $articles_id) {
        $this->contractRepository->associateWithArticles($contract, $articles_id);
    }

}