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

    public function searchClientByTerms($searchTerms) {

        return Client::where('id_number', 'LIKE', "$searchTerms%")->orWhere(function($query) use ($searchTerms) {
            $terms = explode(' ', $searchTerms);
            foreach($terms as $term){
                $query->where('name', 'LIKE', "%$term%");
            }
        })->get();

    }

}