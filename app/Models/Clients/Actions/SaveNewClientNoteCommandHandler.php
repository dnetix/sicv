<?php

namespace App\Models\Clients\Actions;

use App\Models\Clients\ClientNote;
use App\Models\Clients\ClientRepository;
use App\Models\Core\Commander\CommandHandler;

class SaveNewClientNoteCommandHandler implements CommandHandler
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function handle($command)
    {
        $clientNote = new ClientNote();
        $this->fillClientNoteFields($clientNote, $command);
        $this->saveClientNote($clientNote);
        return $clientNote;
    }

    private function fillClientNoteFields(&$clientNote, $command)
    {
        $clientNote->fill((array)$command);
    }

    private function saveClientNote($clientNote)
    {
        $this->clientRepository->saveClientNote($clientNote);
    }
}
