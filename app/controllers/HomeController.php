<?php

class HomeController extends BaseController {

	public function index(){
		if(Auth::check()){
			return Redirect::route('user.dashboard');
		}else{
			return Redirect::route('user.login');
		}
	}

	public function dashboard(){
		return View::make('user.dashboard');
	}

	public function preview($template){
		return View::make('preview.'.$template);
	}

}
