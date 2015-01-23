<?php  namespace SICV\Contracts\Actions;

use SICV\Contracts\ContractRepository;
use SICV\Contracts\PreSellout;
use SICV\Core\Commander\CommandHandler;

class ToggleContractPreSelloutCommandHandler implements CommandHandler {

    /**
     * @var ContractRepository
     */
    private $contractRepository;

    function __construct(ContractRepository $contractRepository) {
        $this->contractRepository = $contractRepository;
    }

    public function handle($command) {
        $contract = $this->contractRepository->getContractById($command->contract_id);
        $response['contractId'] = $contract->id();
        //TODO Refactor to use the repositories
        if($contract->isPreSellout()){
            $contract->preSellout->delete();
            $response['added'] = false;
        }else{
            $contract->preSellout()->save(new PreSellout());
            $response['added'] = true;
        }

        $response['ok'] = true;

        return $response;
    }

}