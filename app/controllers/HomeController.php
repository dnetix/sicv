<?php

use SICV\Contracts\ContractRepository;
use SICV\Core\Commander\CommandBus;

class HomeController extends BaseController {


	/**
	 * @var ContractRepository
	 */
	private $contractRepository;

	function __construct(ContractRepository $contractRepository, CommandBus $commandBus) {
		$this->contractRepository = $contractRepository;
		parent::__construct($commandBus);
	}

	public function index(){
		if(Auth::check()){
			return Redirect::route('user.dashboard');
		}else{
			return Redirect::route('user.login');
		}
	}

	public function dashboard(){
		$data['contracts'] = $this->contractRepository->getContractsOfDay();
		return View::make('user.dashboard', $data);
	}

	public function search(){
		$searchTerms = Input::get('searchTerms');
		try {
			$contract = $this->contractRepository->getContractById($searchTerms);
			return Redirect::route('contract.view', $contract->id());
		} catch (Exception $e) {

			// Its not a contract ...
			return Redirect::route('user.dashboard');

		}
	}

	public function goldprice(){
		$data['goldprice'] = (new \SICV\Utils\DataMining\GoldPrice\GoldPriceRateMiner())->getGoldInformation();
		return View::make('preview._gold_price', $data);
	}

	public function preview($template){
		return View::make('preview.'.$template);
	}

}
