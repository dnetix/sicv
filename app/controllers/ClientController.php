<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SICV\Clients\Actions\EditClientInformationCommand;
use SICV\Clients\Actions\RegisterNewClientCommand;
use SICV\Clients\Actions\SaveNewClientNoteCommand;
use SICV\Clients\Actions\ToggleClientFlagCommand;
use SICV\Clients\ClientRepository;
use SICV\Clients\Exceptions\ClientAlreadyExistsException;
use SICV\Core\Commander\CommandBus;
use SICV\Contracts\ContractRepository;

class ClientController extends BaseController {

	private $clientRepository;
	/**
	 * @var ContractRepository
	 */
	private $contractRepository;

	function __construct(ClientRepository $clientRepository, ContractRepository $contractRepository, CommandBus $commandBus) {
		$this->clientRepository = $clientRepository;
		$this->contractRepository = $contractRepository;
		parent::__construct($commandBus);
	}

	public function create(){
		// Shows the form to create a new Client
		return View::make('client/client_new');
	}

	public function view($id){
		$data = [];

		try {
			$client = $this->clientRepository->getClientById($id);
		} catch (ModelNotFoundException $e) {
			// No existe el cliente
			Flash::overlay()->error("No existe el cliente que ingres&oacute;");
			return Redirect::route('user.dashboard');
		}

		$data['client'] = &$client;
		$data['contracts'] = $this->contractRepository->getContractsOfClient($client);
		$data['clientNotes'] = $this->clientRepository->getClientNotes($client);

		return View::make('client/client_view', $data);
	}

	public function profile($id = null){
		if(is_null($id)){
			$id = Input::get('id');
		}
		$client = $this->clientRepository->getClientById($id);
		if(Input::has('edit')){
			return View::make('client.partials._client_profile_edit')->with('client', $client);
		}else{
			return View::make('client.partials._client_profile')->with('client', $client);
		}
	}

	public function store(){
		$command = new RegisterNewClientCommand(Input::all());

		try {
			$client = $this->execute($command);
		}catch (ClientAlreadyExistsException $e){
			Flash::overlay()->warning('El cliente con ID: <strong>'.$e->getClient()->getIdNumber().'</strong> ya existe', 'Cliente ya existe');
			return Redirect::back()->withInput();
		}

		return Redirect::route('client.view', $client->id());
	}

	public function toggleFlag(){
		$command = new ToggleClientFlagCommand(Input::get('client_id'), Auth::id());
		$client = $this->execute($command);
		return Redirect::route('client.view', $client->id());
	}

	public function edit($id = null){
		if(is_null($id)){
			$id = Input::get('id');
		}
		$command = new EditClientInformationCommand($id, Input::all());
		try {
			$client = $this->execute($command);
		}catch (ClientAlreadyExistsException $e){
			Flash::overlay()->warning('El cliente con ID: <strong>'.$e->getClient()->getIdNumber().'</strong> ya existe', 'Cliente ya existe');
			return Redirect::back()->withInput();
		}

		if(Request::ajax()){
			return View::make('client.partials._client_profile')->with('client', $client);
		}else{
			return Redirect::route('client.view', $client->id());
		}

	}

	public function search(){
		$searchTerms = Input::get('terms');
		$link = Input::get('link');

		$clients = $this->clientRepository->searchClientByTerms($searchTerms);

		return View::make('client.partials._client_search')->with(['clients' => $clients, 'link' => $link]);
	}

	public function note(){
		$command = new SaveNewClientNoteCommand(
			Input::get('client_id'),
			Auth::id(),
			Input::get('note'),
			(Input::has('contract_id') ? Input::get('contract_id') : null),
			Input::get('importance')
		);
		$clientNote = $this->execute($command);
		return View::make('client.partials._client_notes_item')->with('clientNote', $clientNote);
	}

	public function notes(){
		$client = $this->clientRepository->getClientById(Input::get('client_id'));
		$clientNotes = $this->clientRepository->getClientNotes($client);

		$data['client'] =& $client;
		$data['clientNotes'] =& $clientNotes;
		$data['viewOnly'] = true;

		return View::make('client.partials._client_notes', $data);
	}

}
