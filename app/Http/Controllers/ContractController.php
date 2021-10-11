<?php

namespace App\Http\Controllers;

use App\Helpers\RepositoryHelper;
use App\Models\Clients\Client;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function create(Request $request, ?Client $client = null)
    {
        return view('contract.create', [
            'client' => $client,
            'articleTypes' => RepositoryHelper::forArticles()->getArticleTypes(),
        ]);
    }
}
