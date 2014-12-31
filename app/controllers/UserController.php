<?php

class UserController extends BaseController {

	public function login(){
		if(!Auth::check()) {
			return View::make('user.login');
		}else{
			return Redirect::route('user.dashboard');
		}
	}

	public function authenticate(){
		if(Auth::attempt(Input::only('username', 'password'))){
			Flash::overlay()->success("Se ha loguedo exitosamente");
			return Redirect::route('user.dashboard');
		}else{
			Flash::error('Los datos que ha proporcionado no son correctos');
			return Redirect::back()->withInput();
		}
	}

	public function logout(){
		Auth::logout();
		return Redirect::route('user.login');
	}

}
