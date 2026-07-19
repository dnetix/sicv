<?php

namespace App\Queries;

use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Models\ContractExtension;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\StoreItem;
use Illuminate\Support\Carbon;

/**
 * Cash movements over a period, with the exact composition of the legacy
 * financial report: in = extension payments + store sales + redemptions,
 * out = new loans + expenses + direct purchases.
 */
class SummarizeCashFlow
{
    /**
     * @param  array{0: Carbon, 1: Carbon}  $bounds
     */
    public function __invoke(array $bounds): CashFlowSummary
    {
        $redemptions = Contract::query()
            ->where('status', ContractStatus::Redeemed)
            ->whereBetween('ended_at', $bounds)
            ->selectRaw('COALESCE(SUM(amount), 0) AS capital, COALESCE(SUM(settled_amount), 0) AS collected')
            ->first();

        return new CashFlowSummary(
            in: [
                'extensions' => (int) ContractExtension::query()->whereBetween('paid_at', $bounds)->sum('amount'),
                'sales' => (int) Sale::query()->whereBetween('sold_at', $bounds)->sum('total'),
                'redemptions' => (int) $redemptions->collected,
            ],
            out: [
                'loans' => (int) Contract::query()->whereBetween('started_at', $bounds)->sum('amount'),
                'expenses' => (int) Expense::query()->whereBetween('spent_at', $bounds)->sum('amount'),
                // Direct purchases only: foreclosed items' cost is the loan,
                // already counted when the contract was created.
                'purchases' => (int) StoreItem::query()->whereNull('contract_id')->whereBetween('created_at', $bounds)->sum('cost'),
            ],
            redeemedCapital: (int) $redemptions->capital,
        );
    }
}
