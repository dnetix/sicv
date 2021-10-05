<?php

namespace App\Models\Clients\Actions;

use App\Models\Clients\Client;
use App\Models\Clients\ClientRepository;
use App\Models\Clients\Events\ClientWasRegistered;
use App\Models\Clients\Exceptions\ClientAlreadyExistsException;
use App\Models\Core\Commander\CommandHandler;
use App\Models\Core\Commander\Eventing\EventDispatcher;
use App\Models\Core\Commander\Eventing\EventGenerator;
use Illuminate\Database\QueryException;

class RegisterNewClientCommandHandler implements CommandHandler
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
        $client = new Client();

        try {
            $this->registerNewClient($client, $command);

            $this->raise(new ClientWasRegistered($client));
            $this->eventDispatcher->dispatch($this->releaseEvents());
        } catch (QueryException $e) {
            throw new ClientAlreadyExistsException('Client already exists', $this->getClientByIdNumber($command->id_number));
        }

        return $client;
    }

    protected function registerNewClient(&$client, $command)
    {
        $client->fill((array)$command);
        $client = $this->clientRepository->register($client);
    }

    protected function getClientByIdNumber($idNumber)
    {
        return $this->clientRepository->getClientByIdNumber($idNumber);
    }
}
