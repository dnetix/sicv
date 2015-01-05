<?php  namespace SICV\Clients\Exceptions;

use SICV\Clients\Client;

class ClientAlreadyExistsException extends \Exception {

    protected $client;

    /**
     * @param string $message
     * @param Client $client
     */
    function __construct($message, Client $client)
    {
        $this->client = $client;
        parent::__construct($message);
    }

    public function getClient(){
        return $this->client;
    }

}