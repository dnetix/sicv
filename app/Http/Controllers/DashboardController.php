<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractExtension;
use App\Queries\SummarizeCashFlow;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Home screen: today's cash-flow indicators, the expired-contracts
     * counter, today's new contracts with a running total (as the legacy
     * menu showed) and today's extension payments.
     */
    public function __invoke(SummarizeCashFlow $summarize): View
    {
        $todayBounds = [today(), now()->endOfDay()];

        $todayContracts = Contract::query()
            ->with('client')
            ->whereBetween('started_at', $todayBounds)
            ->orderByDesc('id')
            ->get();

        $todayExtensions = ContractExtension::query()
            ->with('contract.client')
            ->whereBetween('paid_at', $todayBounds)
            ->orderByDesc('paid_at')
            ->limit(8)
            ->get();

        return view('dashboard', [
            'cashFlow' => $summarize($todayBounds),
            'expiredCount' => Contract::query()->expired()->notQueued()->count(),
            'todayContracts' => $todayContracts,
            'todayExtensions' => $todayExtensions,
        ]);
    }
}
