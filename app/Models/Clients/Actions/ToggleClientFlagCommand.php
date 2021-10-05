<?php

namespace App\Models\Clients\Actions;

use App\Models\Core\Commander\Command;

class ToggleClientFlagCommand extends Command
{
    public $client_id;
    public $user_id;

    public function __construct($client_id, $user_id)
    {
        $this->client_id = $client_id;
        $this->user_id = $user_id;
    }
}
