<?php

use SICV\Contracts\ContractRepository;

class HomeController extends BaseController {

	public function index(){
		if(Auth::check()){
			return Redirect::route('user.dashboard');
		}else{
			return Redirect::route('user.login');
		}
	}

	public function dashboard(){
		$contractRepository = App::make(ContractRepository::class);
		$data['contracts'] = $contractRepository->getContractsOfDay();
		return View::make('user.dashboard', $data);
	}

	public function preview($template){
		return View::make('preview.'.$template);
	}

}
