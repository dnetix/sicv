<?php

namespace App\Http\Controllers;

use App\Enums\ContractStatus;
use App\Http\Requests\StoreContractRequest;
use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Contract;
use App\Models\ItemType;
use App\Support\Code128;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    /**
     * Default terms, unchanged from the legacy system.
     */
    public const int DEFAULT_TERM_MONTHS = 4;

    public const float DEFAULT_MONTHLY_RATE = 10.0;

    public function create(Request $request): View
    {
        return view('contracts.create', [
            'client' => $request->filled('client')
                ? Client::query()
                    ->with('notes.user')
                    ->withCount('contracts')
                    ->find($request->integer('client'))
                    ?->searchPayload()
                : null,
            'itemTypes' => ItemType::query()->orderBy('name')->get(),
            'defaultTerm' => self::DEFAULT_TERM_MONTHS,
            'defaultRate' => self::DEFAULT_MONTHLY_RATE,
        ]);
    }

    public function store(StoreContractRequest $request): RedirectResponse
    {
        $contract = Contract::query()->create([
            ...$request->validated(),
            'status' => ContractStatus::Active,
            'started_at' => now(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('contracts.print', $contract)
            ->with('status', "Contrato No. {$contract->id} creado exitosamente.");
    }

    public function show(Contract $contract): View
    {
        $contract->load([
            'client.notes.user', 'itemType', 'user',
            'extensions' => fn ($query) => $query->orderBy('paid_at')->with('user'),
            'void.user',
        ]);

        return view('contracts.show', [
            'contract' => $contract,
            'saleInfo' => $contract->status === ContractStatus::Sold ? $contract->saleInfo() : null,
            'barcode' => Code128::encode((string) $contract->id),
        ]);
    }

    /**
     * Printable legal contract. Reprints of any status are allowed (the
     * legacy app did the same); explicit copies carry a DUPLICADO watermark.
     */
    public function print(Contract $contract, Request $request): View
    {
        $contract->load(['client', 'extensions']);

        return view('contracts.print', [
            'contract' => $contract,
            'company' => CompanySetting::current(),
            'barcode' => Code128::encode((string) $contract->id),
            'isCopy' => $request->boolean('copy'),
        ]);
    }
}
