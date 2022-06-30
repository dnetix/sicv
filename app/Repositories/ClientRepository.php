<?php

namespace App\Repositories;

use App\Models\Clients\Client;
use App\Models\Clients\ClientNote;
use Illuminate\Database\Eloquent\Collection;

class ClientRepository
{
    public function save(Client $client): Client
    {
        $client->save();
        return $client;
    }

    public function getClientByDocument($document): Client
    {
        return Client::whereDocument($document)->firstOrFail();
    }

    public function getClientById($id): Client
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
        return Client::where('document', 'LIKE', "$searchTerms%")
            ->orWhere(function ($query) use ($searchTerms) {
                $terms = explode(' ', $searchTerms);
                foreach ($terms as $term) {
                    $query->where('name', 'LIKE', "%$term%");
                }
            })->limit($limit)->get();
    }

    public function storeClientNote(ClientNote $clientNote): ClientNote
    {
        $clientNote->save();
        return $clientNote;
    }
}
