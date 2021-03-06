<?php namespace SICV\Clients\Actions;

use Illuminate\Database\QueryException;
use SICV\Clients\Client;
use SICV\Clients\ClientRepository;
use SICV\Clients\Events\ClientWasRegistered;
use SICV\Clients\Exceptions\ClientAlreadyExistsException;
use SICV\Core\Commander\CommandHandler;
use SICV\Core\Commander\Eventing\EventDispatcher;
use SICV\Core\Commander\Eventing\EventGenerator;

class EditClientInformationCommandHandler implements CommandHandler {

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

        $client = $this->findClientById($command);
        $this->fillClientInformation($client, $command);
        try {
            $this->updateClientInformation($client);
        }catch (QueryException $e){
            throw new ClientAlreadyExistsException("Client already exists", $client);
        }

        return $client;

    }

    private function fillClientInformation(&$client, $command) {
        foreach($command->fieldsToEdit as $field){
            $client->{$field} = $command->{$field};
        }
    }

    /**
     * @param $command
     * @return Client
     */
    protected function findClientById($command) {
        return $this->clientRepository->getClientById($command->id);
    }

    /**
     * @param $client
     */
    public function updateClientInformation($client) {
        $this->clientRepository->update($client);
    }


}