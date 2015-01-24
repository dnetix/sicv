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

        $contract->fill($command->toAttributes());
        $contract->toActive();

        $this->contractRepository->save($contract);
        $this->contractRepository->associateWithArticles($contract, $command->articlesWithAmount());

        return $contract;

    }

}