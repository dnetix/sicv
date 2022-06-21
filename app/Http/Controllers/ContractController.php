<?php

namespace App\Http\Controllers;

use App\Helpers\RepositoryHelper;
use App\Http\Requests\ContractCreateRequest;
use App\Models\Clients\Client;
use App\Models\Contracts\Actions\CreateNewContractAction;
use App\Models\Contracts\Contract;
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

    public function store(ContractCreateRequest $request)
    {
        $contract = (new CreateNewContractAction(
            auth()->user()->getAuthIdentifier(),
            $request->clientId(),
            $request->months(),
            $request->percentage(),
            $request->articles(),
            $request->note()
        ))->execute();

        return redirect(route('contract.print', $contract->id()));
    }

    public function print(Contract $contract)
    {
        return view('contract.print', [
            'contract' => $contract,
            'articles' => RepositoryHelper::forContracts()->getContractArticles($contract),
        ]);
    }
}
