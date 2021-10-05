<?php

namespace App\Models\Clients\Exceptions;

use App\Models\Clients\Client;

class ClientAlreadyExistsException extends \Exception
{
    protected $client;

    /**
     * @param string $message
     * @param Client $client
     */
    public function __construct($message, Client $client)
    {
        $this->client = $client;
        parent::__construct($message);
    }

    public function getClient()
    {
        return $this->client;
    }
}
