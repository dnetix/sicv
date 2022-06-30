<?php

namespace App\Http\Controllers;

use App\Helpers\RepositoryHelper;
use App\Http\Requests\ContractCreateRequest;
use App\Http\Requests\CreateClientNoteRequest;
use App\Http\Requests\CreateContractExtensionRequest;
use App\Models\Clients\Actions\CreateNewClientNoteAction;
use App\Models\Clients\Client;
use App\Models\Contracts\Actions\CreateNewContractAction;
use App\Models\Contracts\Actions\CreateNewExtensionAction;
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

    public function view(Contract $contract)
    {
        return view('contract.view', [
            'contract' => $contract,
            'client' => $contract->client,
            'extensions' => $contract->extensions,
            'notes' => RepositoryHelper::forClients()->getClientNotes($contract->client),
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

    public function extension(CreateContractExtensionRequest $request, Contract $contract)
    {
        (new CreateNewExtensionAction($contract->id(), $request->amount(), auth()->id()))->execute();
        return redirect(route('contract.view', ['contract' => $contract->id()]));
    }

    public function note(CreateClientNoteRequest $request, Contract $contract)
    {
        (new CreateNewClientNoteAction($contract->clientId(), auth()->id(), $request->note(), $request->importance(), $contract->id()))->execute();
        return redirect(route('contract.view', ['contract' => $contract->id()]));
    }

    public function print(Contract $contract)
    {
        return view('contract.print', [
            'contract' => $contract,
            'articles' => RepositoryHelper::forContracts()->getContractArticles($contract),
        ]);
    }
}
