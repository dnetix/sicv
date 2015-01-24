<?php

use SICV\Contracts\Actions\ToggleContractPreSelloutCommand;
use SICV\Contracts\ContractRepository;
use SICV\Core\Commander\CommandBus;
use SICV\Reports\Actions\RetrievePreSelloutStatisticsCommand;

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

    public function presellouts(){

        $contracts = $this->contractRepository->getPreselloutContracts();

        $command = new RetrievePreSelloutStatisticsCommand($contracts);
        $data['contractStatistics'] = $this->execute($command);
        $data['contracts'] =& $contracts;
        $data['kindStatistics'] = 'presellouts';

        return View::make('sellout.presellout_contracts', $data);
    }

    public function process(){

        //TODO Create the command
        $contracts = $this->contractRepository->getPreselloutContracts();

        $articlesSuggestions = [];

        $moveGoldAsProduct = Config::get('sicv.sellouts.move_gold_as_product');

        foreach($contracts as $contract){
            $articles = $contract->articles;
            foreach($articles as $article){
                if(!$article->isGold() || $moveGoldAsProduct) {
                    $articlesSuggestions[] = (new \SICV\Sellouts\ProductContractSuggestor())->suggest(
                        $contract,
                        $article
                    );
                }
            }
        }

        $data['suggestions'] =& $articlesSuggestions;
        return View::make('sellout.sellout_prices_suggestions', $data);

    }

}
