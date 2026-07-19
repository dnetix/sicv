<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuickSearchController extends Controller
{
    /**
     * Global quick-search dispatcher, matching the legacy prefixes printed
     * on the documents: NC{id} → sale, CL{document} → client, a bare
     * number → contract. Anything else lands on the client search.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $query = trim($request->string('q'));

        if (preg_match('/NC\s*(\d+)/i', $query, $matches) === 1) {
            $sale = Sale::query()->find($matches[1]);

            return $sale !== null
                ? redirect()->route('sales.show', $sale)
                : $this->notFound("No existe la nota de cobro NC{$matches[1]}.");
        }

        if (preg_match('/CL\s*(\S+)/i', $query, $matches) === 1) {
            $client = Client::query()->where('document_number', $matches[1])->first();

            return $client !== null
                ? redirect()->route('clients.show', $client)
                : $this->notFound("No existe un cliente con documento {$matches[1]}.");
        }

        if (ctype_digit($query)) {
            $contract = Contract::query()->find($query);

            return $contract !== null
                ? redirect()->route('contracts.show', $contract)
                : $this->notFound("No existe el contrato No. $query.");
        }

        return redirect()->route('clients.index', ['q' => $query]);
    }

    private function notFound(string $message): RedirectResponse
    {
        return redirect()->route('dashboard')->with('error', $message);
    }
}
