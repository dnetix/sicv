<?php

class HomeController extends BaseController {

	public function dashboard(){
		return View::make('dashboard');
	}

	public function preview($template){
		return View::make('preview.'.$template);
	}

}
