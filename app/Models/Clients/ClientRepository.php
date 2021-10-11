<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Collection;

class ClientRepository
{
    public function register(Client $client)
    {
        $client->save();
        return $client;
    }

    public function getClientByIdNumber($idNumber)
    {
        return Client::whereIdNumber($idNumber)->firstOrFail();
    }

    /**
     * @param $id
     * @return \App\Models\Clients\Client
     * @throws \Illuminate\Database\QueryException
     */
    public function getClientById($id)
    {
        return Client::findOrFail($id);
    }

    public function getClientNotes(Client $client, $contract_id = null)
    {
        if (is_null($contract_id)) {
            return $client->notes()->with('user')->orderBy('id', 'desc')->get();
        } else {
            return $client->notes()->with('user')->where(function ($query) use ($contract_id) {
                $query->whereNull('contract_id');
                $query->orWhere('contract_id', $contract_id);
            })->orderBy('contract_id', 'desc')->orderBy('id', 'desc')->get();
        }
    }

    public function update(Client $client)
    {
        $client->update();
        return $client;
    }

    public function searchClientByTerms(string $searchTerms, int $limit = 10): Collection
    {
        return Client::where('id_number', 'LIKE', "$searchTerms%")
            ->orWhere(function ($query) use ($searchTerms) {
                $terms = explode(' ', $searchTerms);
                foreach ($terms as $term) {
                    $query->where('name', 'LIKE', "%$term%");
                }
            })->limit($limit)->get();
    }

    public function saveClientNote(ClientNote &$clientNote)
    {
        return $clientNote->save();
    }
}
