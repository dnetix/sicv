<?php namespace SICV\Clients;

class ClientRepository {

    public function register(Client $client){
        $client->save();
        return $client;
    }

    public function getClientByIdNumber($idNumber) {
        return Client::whereIdNumber($idNumber)->firstOrFail();
    }

    public function getClientById($id){
        return Client::findOrFail($id);
    }

    public function update(Client $client) {
        $client->update();
        return $client;
    }

}