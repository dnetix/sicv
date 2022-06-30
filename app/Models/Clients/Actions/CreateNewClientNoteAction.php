<?php

namespace App\Models\Clients\Actions;

use App\Helpers\RepositoryHelper;
use App\Models\Clients\ClientNote;

class CreateNewClientNoteAction
{
    private int $client_id;
    private int $user_id;
    private string $note;
    private string $importance;
    private ?int $contract_id;

    public function __construct(int $client_id, int $user_id, string $note, string $importance, ?int $contract_id)
    {
        $this->client_id = $client_id;
        $this->user_id = $user_id;
        $this->note = $note;
        $this->importance = $importance;
        $this->contract_id = $contract_id;
    }

    public function execute(): ClientNote
    {
        $note = new ClientNote([
            'note' => $this->note,
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'contract_id' => $this->contract_id,
            'importance' => $this->importance,
        ]);
        return RepositoryHelper::forClients()->storeClientNote($note);
    }
}
