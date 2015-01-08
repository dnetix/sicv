<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SICV\Clients\Actions\EditClientInformationCommand;
use SICV\Clients\Actions\RegisterNewClientCommand;
use SICV\Clients\ClientRepository;
use SICV\Clients\Exceptions\ClientAlreadyExistsException;
use SICV\Commander\CommandBus;

class ClientController extends BaseController {

	private $clientRepository;

	function __construct(ClientRepository $clientRepository, CommandBus $commandBus) {
		$this->clientRepository = $clientRepository;
		parent::__construct($commandBus);
	}

	public function create(){
		// Shows the form to create a new Client
		return View::make('client/client_new');
	}

	public function view($id){
		$data = [];

		try {
			$data['client'] = $this->clientRepository->getClientById($id);
		} catch (ModelNotFoundException $e) {
			// No existe el cliente
			Flash::overlay()->error("No existe el cliente que ingres&oacute;");
			return Redirect::route('user.dashboard');
		}

		return View::make('client/client_view', $data);
	}

	public function profile($id = null){
		if(is_null($id)){
			$id = Input::get('id');
		}
		$client = $this->clientRepository->getClientById($id);
		return View::make('client.partials._client_profile')->with('client', $client);
	}

	public function store(){
		$command = new RegisterNewClientCommand(Input::all());

		try {
			$client = $this->execute($command);
		}catch (ClientAlreadyExistsException $e){
			Flash::overlay()->warning('El cliente con ID: <strong>'.$e->getClient()->getIdNumber().'</strong> ya existe', 'Cliente ya existe');
			return Redirect::back()->withInput();
		}

		return Redirect::route('client.view', $client->getId());
	}

	public function edit($id){
		$command = new EditClientInformationCommand($id, Input::all());
		try {
			$client = $this->execute($command);
		}catch (ClientAlreadyExistsException $e){
			Flash::overlay()->warning('El cliente con ID: <strong>'.$e->getClient()->getIdNumber().'</strong> ya existe', 'Cliente ya existe');
			return Redirect::back()->withInput();
		}

		return Redirect::route('client.view', $client->getId());
	}

	public function search(){
		$searchTerms = Input::get('terms');
		$link = Input::get('link');

		$clients = $this->clientRepository->searchClientByTerms($searchTerms);

		return View::make('client.partials._client_search')->with(['clients' => $clients, 'link' => $link]);

	}

}
