<?php

namespace App\Models\Clients\Actions;

use App\Models\Clients\Client;
use App\Models\Clients\ClientRepository;
use App\Models\Clients\Exceptions\ClientAlreadyExistsException;
use App\Models\Core\Commander\CommandHandler;
use App\Models\Core\Commander\Eventing\EventDispatcher;
use App\Models\Core\Commander\Eventing\EventGenerator;
use Illuminate\Database\QueryException;

class EditClientInformationCommandHandler implements CommandHandler
{
    use EventGenerator;

    protected $clientRepository;
    /**
     * @var
     */
    private $eventDispatcher;

    public function __construct(ClientRepository $clientRepository, EventDispatcher $eventDispatcher)
    {
        $this->clientRepository = $clientRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $command
     * @return Client
     * @throws ClientAlreadyExistsException
     */
    public function handle($command)
    {
        $client = $this->findClientById($command);
        $this->fillClientInformation($client, $command);
        try {
            $this->updateClientInformation($client);
        } catch (QueryException $e) {
            throw new ClientAlreadyExistsException('Client already exists', $client);
        }

        return $client;
    }

    private function fillClientInformation(&$client, $command)
    {
        foreach ($command->fieldsToEdit as $field) {
            $client->{$field} = $command->{$field};
        }
    }

    /**
     * @param $command
     * @return Client
     */
    protected function findClientById($command)
    {
        return $this->clientRepository->getClientById($command->id);
    }

    /**
     * @param $client
     */
    public function updateClientInformation($client)
    {
        $this->clientRepository->update($client);
    }
}
