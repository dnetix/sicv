<?php

namespace App\Http\Controllers;

use App\Helpers\RepositoryHelper;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function create()
    {
        return view('client.create', [
        ]);
    }

    public function search(Request $request)
    {
        return RepositoryHelper::forClients()
            ->searchClientByTerms($request->get('terms'))
            ->keyBy('id');
    }
}
