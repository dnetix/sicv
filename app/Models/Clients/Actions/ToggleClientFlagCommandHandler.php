<?php

namespace App\Models\Clients\Actions;

use App\Models\Clients\Client;
use App\Models\Clients\ClientRepository;
use App\Models\Core\Commander\CommandHandler;

class ToggleClientFlagCommandHandler implements CommandHandler
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function handle($command)
    {
        $client = $this->clientRepository->getClientById($command->client_id);
        $this->toggleUserFlag($client);
        $this->updateClient($client);
        return $client;
    }

    private function toggleUserFlag(Client &$client)
    {
        if ($client->isFlagged()) {
            $client->flagged = null;
        } else {
            $client->flagged = 1;
        }
    }

    private function updateClient(Client &$client)
    {
        $this->clientRepository->update($client);
    }
}
