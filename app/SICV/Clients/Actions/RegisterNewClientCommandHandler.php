<?php namespace SICV\Clients\Actions;

use Illuminate\Database\QueryException;
use SICV\Clients\Client;
use SICV\Clients\ClientRepository;
use SICV\Clients\Events\ClientWasRegistered;
use SICV\Clients\Exceptions\ClientAlreadyExistsException;
use SICV\Core\Commander\CommandHandler;
use SICV\Core\Commander\Eventing\EventDispatcher;
use SICV\Core\Commander\Eventing\EventGenerator;

class RegisterNewClientCommandHandler implements CommandHandler {

    use EventGenerator;

    protected $clientRepository;
    /**
     * @var
     */
    private $eventDispatcher;

    function __construct(ClientRepository $clientRepository, EventDispatcher $eventDispatcher) {
        $this->clientRepository = $clientRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $command
     * @return Client
     * @throws ClientAlreadyExistsException
     */
    public function handle($command){

        $client = new Client();

        try {
            $this->registerNewClient($client, $command);

            $this->raise(new ClientWasRegistered($client));
            $this->eventDispatcher->dispatch($this->releaseEvents());

        }catch (QueryException $e){
            throw new ClientAlreadyExistsException("Client already exists", $this->getClientByIdNumber($command->id_number));
        }

        return $client;

    }

    protected function registerNewClient(&$client, $command){
        $client->fill((array) $command);
        $client = $this->clientRepository->register($client);
    }

    protected function getClientByIdNumber($idNumber){
        return $this->clientRepository->getClientByIdNumber($idNumber);
    }

}