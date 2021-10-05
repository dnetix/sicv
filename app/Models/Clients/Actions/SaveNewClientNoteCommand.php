<?php

namespace App\Models\Clients\Actions;

use App\Models\Core\Commander\Command;

class SaveNewClientNoteCommand extends Command
{
    public $client_id;
    public $user_id;
    public $note;
    public $contract_id;
    public $importance;

    public function __construct($client_id, $user_id, $note, $contract_id = null, $importance = 'info')
    {
        $this->client_id = $client_id;
        $this->user_id = $user_id;
        $this->note = $note;
        $this->contract_id = $contract_id;
        $this->importance = (empty($importance) ? 'info' : $importance);
    }
}
