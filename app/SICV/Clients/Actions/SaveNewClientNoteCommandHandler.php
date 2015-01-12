<?php  namespace SICV\Clients\Actions;

use SICV\Clients\ClientNote;
use SICV\Clients\ClientRepository;
use SICV\Core\Commander\CommandHandler;

class SaveNewClientNoteCommandHandler implements CommandHandler {

    private $clientRepository;

    function __construct(ClientRepository $clientRepository) {
        $this->clientRepository = $clientRepository;
    }

    public function handle($command) {
        $clientNote = new ClientNote();
        $this->fillClientNoteFields($clientNote, $command);
        $this->saveClientNote($clientNote);
        return $clientNote;
    }

    private function fillClientNoteFields(&$clientNote, $command) {
        $clientNote->fill((array) $command);
    }

    private function saveClientNote($clientNote) {
        $this->clientRepository->saveClientNote($clientNote);
    }

}