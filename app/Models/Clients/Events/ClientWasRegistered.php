<?php

namespace App\Models\Clients\Events;

class ClientWasRegistered
{
    public $client;

    public function __construct($client)
    {
        $this->client = $client;
    }
}
