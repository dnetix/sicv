<?php

use SICV\Contracts\Actions\ToggleContractPreSelloutCommand;
use SICV\Contracts\ContractRepository;
use SICV\Core\Commander\CommandBus;
use SICV\Reports\Actions\RetrievePreSelloutStatisticsCommand;
use SICV\Sales\Product;
use SICV\Sellouts\Sellout;

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

    public function create(){
        // Receive sell prices its an array contract_article_id => sell_price
        $sellPrices = Input::get('sell_price');

        $contracts = $this->contractRepository->getPreselloutContracts();

        if($contracts->count() == 0){
            Flash::overlay()->error("Nada que sacar");
            return Redirect::route('user.dashboard');
        }

        $moveGoldAsProduct = Config::get('sicv.sellouts.move_gold_as_product');

        DB::beginTransaction();

        $sellout = (new Sellout())->setUserId(Auth::id())->setNote(Input::get('note'));
        $sellout->save();
        $goldWeight = 0;

        foreach($contracts as $contract){
            // Change the contract state to ENDED
            $contract->toEnded()->setEndDate(Date::create()->toSQLTimestamp())->save();
            // Make the relationship with the sellout
            $sellout->contracts()->attach($contract);

            //TODO Change this to a new command or make it most explicit
            $command = new ToggleContractPreSelloutCommand($contract->id());
            $this->execute($command);

            $articles = $contract->articles;
            foreach($articles as $article){
                // Create the products
                if(!$article->isGold() || $moveGoldAsProduct) {
                    //TODO Do this in a repository
                    $product = (new Product())
                        ->setArticleId($article->id())
                        ->setBuyPrice($article->pivot->article_amount)
                        ->setSellPrice($this->normalizeAmount($sellPrices[$article->pivot->id]))
                        ->setContractId($contract->id()
                        ->setQuantity(1));
                    $product->save();
                }else{
                    $goldWeight += $article->weight();
                }
            }
        }
        $sellout->setGoldWeight($goldWeight)->update();

        DB::commit();
    }

    public function view($id){
        $sellout = Sellout::findOrFail($id);
        $contracts = $sellout->contracts()->with(['client', 'extensions', 'articles'])->get();

        $command = new RetrievePreSelloutStatisticsCommand($contracts);
        $data['contractStatistics'] = $this->execute($command);

        $data['contracts'] =& $contracts;

        $data['sellout'] =& $sellout;
        return View::make('sellout.sellout_view', $data);
    }

}
