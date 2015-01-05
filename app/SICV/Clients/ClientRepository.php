<?php namespace SICV\Clients;

class ClientRepository {

    public function register(Client $client){

        $client->save();
        return $client;

    }

    public function getClientByIdNumber($idNumber) {
        return Client::whereIdNumber($idNumber)->firstOrFail();
    }

}