<?php

use SICV\Clients\Actions\RegisterNewClientCommand;
use SICV\Clients\Exceptions\ClientAlreadyExistsException;

class ClientController extends BaseController {

	public function create(){
		// Shows the form to create a new Client
		return View::make('client/client_new');
	}

	public function view($id){
		echo $id;
	}

	public function store(){
		$command = new RegisterNewClientCommand(Input::all());

		try {
			$client = $this->execute($command);
		}catch (ClientAlreadyExistsException $e){
			Flash::overlay()->warning('El cliente con ID: <strong>'.$e->getClient()->getId().'</strong> ya existe', 'Cliente ya existe');
			return Redirect::back()->withInput();
		}

		return Redirect::route('client.view', $client->getId());

	}

}
