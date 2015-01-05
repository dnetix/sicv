<?php  namespace SICV\Clients\Events;

class ClientWasRegistered {

    public $client;

    function __construct($client) {
        $this->client = $client;
    }

}