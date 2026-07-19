<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Queries\SearchClients;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        return view('clients.index');
    }

    public function search(Request $request, SearchClients $search): JsonResponse
    {
        $clients = $search($request->string('q'), config('sicv.client_search_limit'));

        return response()->json($clients->map(fn (Client $client) => $client->searchPayload()));
    }

    /**
     * City / issue-place autocomplete, sourced (as in the legacy app) from
     * the issue places already registered on other clients.
     */
    public function cities(Request $request): JsonResponse
    {
        $cities = Client::query()
            ->select('document_issue_place')
            ->whereLike('document_issue_place', '%'.$request->string('q').'%')
            ->distinct()
            ->orderBy('document_issue_place')
            ->limit(15)
            ->pluck('document_issue_place');

        return response()->json($cities);
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::query()->create($request->validated());

        return redirect()
            ->route('clients.show', $client)
            ->with('status', 'Se ha guardado el cliente exitosamente.');
    }

    public function show(Client $client): View
    {
        $client->load([
            'notes.user',
            'contracts' => fn ($query) => $query
                ->withSum('extensions', 'amount')
                ->orderByDesc('id'),
            'sales' => fn ($query) => $query->orderByDesc('sold_at'),
        ]);

        return view('clients.show', ['client' => $client]);
    }

    /**
     * Inline creation from the new-contract screen. Mirrors the legacy
     * behavior: if the document already exists the existing client is
     * returned untouched (posted data is ignored) and the UI announces it.
     */
    public function quickStore(Request $request): JsonResponse
    {
        $existing = Client::query()
            ->where('document_number', trim($request->string('document_number')))
            ->first();

        if ($existing !== null) {
            $existing->load('notes.user')->loadCount('contracts');

            return response()->json([
                'existed' => true,
                ...$existing->searchPayload(),
            ]);
        }

        $client = Client::query()->create(
            app(StoreClientRequest::class)->validated()
        );

        return response()->json([
            'existed' => false,
            ...$client->load('notes')->loadCount('contracts')->searchPayload(),
        ], 201);
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()
            ->route('clients.show', $client)
            ->with('status', 'Se han actualizado los datos del cliente.');
    }
}
