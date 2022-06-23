<?php

namespace App\Http\Controllers;

use App\Helpers\RepositoryHelper;
use App\Http\Requests\ClientCreateRequest;
use App\Models\Clients\Actions\CreateNewClientAction;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function create()
    {
        return view('client.create', [
            // TODO: Define where Im going to store this
            'documentTypes' => ['CC' => trans('client.document_type_cc')],
        ]);
    }

    public function store(ClientCreateRequest $request)
    {
        return (new CreateNewClientAction($request->validated()))->execute();
    }

    public function search(Request $request)
    {
        $request->validate([
            'terms' => 'required|string',
        ]);

        return RepositoryHelper::forClients()
            ->searchClientByTerms($request->get('terms'));
    }
}
