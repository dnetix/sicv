<?php  namespace SICV\Clients\Actions;

use SICV\Clients\Client;
use SICV\Clients\ClientRepository;
use SICV\Core\Commander\CommandHandler;

class ToggleClientFlagCommandHandler implements CommandHandler {

    private $clientRepository;

    function __construct(ClientRepository $clientRepository) {
        $this->clientRepository = $clientRepository;
    }

    public function handle($command) {
        $client = $this->clientRepository->getClientById($command->client_id);
        $this->toggleUserFlag($client);
        $this->updateClient($client);
        return $client;
    }

    private function toggleUserFlag(Client &$client) {
        if($client->isFlagged()){
            $client->flagged = null;
        }else{
            $client->flagged = 1;
        }
    }

    private function updateClient(Client &$client) {
        $this->clientRepository->update($client);
    }

}