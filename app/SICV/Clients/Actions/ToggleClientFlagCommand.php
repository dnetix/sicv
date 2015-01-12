<?php  namespace SICV\Clients\Actions;

use SICV\Core\Commander\Command;

class ToggleClientFlagCommand extends Command {

    public $client_id;
    public $user_id;

    function __construct($client_id, $user_id) {
        $this->client_id = $client_id;
        $this->user_id = $user_id;
    }

}