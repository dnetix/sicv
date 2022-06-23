<?php

namespace App\Models\Clients\Actions;

use App\Helpers\RepositoryHelper;
use App\Models\Clients\Client;

class CreateNewClientAction
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function execute(): Client
    {
        return RepositoryHelper::forClients()->save(new Client($this->data));
    }
}
