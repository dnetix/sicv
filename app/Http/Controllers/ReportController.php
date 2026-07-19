<?php

namespace App\Http\Controllers;

use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Models\ContractExtension;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\ItemType;
use App\Models\SaleItem;
use App\Queries\SummarizeCashFlow;
use App\Support\DateRange;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Expired contracts not yet queued for repossession, most-expired first.
     */
    public function expired(): View
    {
        $contracts = Contract::query()
            ->withReportColumns()
            ->expired()
            ->notQueued()
            ->with(['client', 'itemType'])
            ->orderByRaw('view_months_elapsed - extended_months_total DESC')
            ->get();

        return view('reports.expired', ['contracts' => $contracts]);
    }

    /**
     * Queued (pre-saca) contracts, ready for the bulk pull.
     */
    public function queued(): View
    {
        $contracts = Contract::query()
            ->withReportColumns()
            ->expired()
            ->queued()
            ->with(['client', 'itemType', 'repossession'])
            ->orderByDesc('view_months_elapsed')
            ->get();

        return view('reports.queued', [
            'contracts' => $contracts,
            'hasGold' => $contracts->contains(fn (Contract $contract) => $contract->item_type_id === ItemType::GOLD),
        ]);
    }

    public function active(Request $request): View
    {
        $range = DateRange::fromRequest($request);

        $totals = Contract::query()
            ->active()
            ->selectRaw('COUNT(*) AS count, COALESCE(SUM(amount), 0) AS amount')
            ->first();

        $contracts = Contract::query()
            ->withReportColumns()
            ->active()
            ->with(['client', 'itemType'])
            ->when($request->filled('item_type_id'), fn (Builder $query) => $query->where('item_type_id', $request->integer('item_type_id')))
            ->when($request->boolean('filter'), fn (Builder $query) => $query->whereBetween('started_at', $range->bounds()))
            ->orderBy('started_at')
            ->get();

        return view('reports.active', [
            'contracts' => $contracts,
            'totals' => $totals,
            'range' => $range,
            'itemTypes' => ItemType::query()->orderBy('name')->get(),
            'filtered' => $request->boolean('filter'),
        ]);
    }

    public function extensions(Request $request): View
    {
        $range = DateRange::fromRequest($request);

        $extensions = ContractExtension::query()
            ->with(['contract.client', 'user'])
            ->whereBetween('paid_at', $range->bounds())
            ->orderByDesc('paid_at')
            ->get();

        return view('reports.extensions', [
            'extensions' => $extensions,
            'range' => $range,
        ]);
    }

    public function sold(Request $request): View
    {
        $range = DateRange::fromRequest($request);

        $items = SaleItem::query()
            ->with(['sale.client', 'storeItem.contract', 'storeItem.itemType'])
            ->whereHas('sale', fn (Builder $query) => $query->whereBetween('sold_at', $range->bounds()))
            ->when($request->filled('item_type_id'), fn (Builder $query) => $query->whereHas(
                'storeItem',
                fn (Builder $item) => $item->where('item_type_id', $request->integer('item_type_id')),
            ))
            ->orderByDesc('sale_id')
            ->get();

        return view('reports.sold', [
            'items' => $items,
            'range' => $range,
            'itemTypes' => ItemType::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Daily cash summary: money out (loans, expenses, purchases) vs money
     * in (extension payments, store sales, redemptions).
     */
    public function financial(Request $request, SummarizeCashFlow $summarize): View
    {
        $range = DateRange::fromRequest($request, defaultToday: true);
        $summary = $summarize($range->bounds());

        return view('reports.financial', [
            'range' => $range,
            'out' => $summary->out,
            'in' => $summary->in,
            'redeemedCapital' => $summary->redeemedCapital,
        ]);
    }

    public function expenses(Request $request): View
    {
        $range = DateRange::fromRequest($request);

        $expenses = Expense::query()
            ->with(['type', 'user'])
            ->whereBetween('spent_at', $range->bounds())
            ->when($request->filled('expense_type_id'), fn (Builder $query) => $query->where('expense_type_id', $request->integer('expense_type_id')))
            ->orderByDesc('spent_at')
            ->get();

        return view('reports.expenses', [
            'expenses' => $expenses,
            'range' => $range,
            'expenseTypes' => ExpenseType::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Contracts that left as store inventory, sales or scrap.
     */
    public function pulled(Request $request): View
    {
        return $this->closedContractsReport($request, 'reports.pulled', [
            ContractStatus::InStore, ContractStatus::Sold, ContractStatus::Scrapped,
        ]);
    }

    /**
     * Contracts redeemed by their clients.
     */
    public function redeemed(Request $request): View
    {
        return $this->closedContractsReport($request, 'reports.redeemed', [ContractStatus::Redeemed]);
    }

    public function stats(Request $request): View
    {
        $request->validate(['year' => ['nullable', 'integer', 'min:2000', 'max:2100']]);

        $year = $request->integer('year') ?: today()->year;

        $months = Contract::query()
            ->selectRaw('MONTH(started_at) AS month, COUNT(*) AS contracts, COALESCE(SUM(amount), 0) AS amount')
            ->whereBetween('started_at', [
                Carbon::create($year, 1, 1)->startOfDay(),
                Carbon::create($year, 12, 31)->endOfDay(),
            ])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        return view('reports.stats', [
            'year' => $year,
            'months' => $months,
        ]);
    }

    /**
     * @param  array<int, ContractStatus>  $statuses
     */
    private function closedContractsReport(Request $request, string $view, array $statuses): View
    {
        $range = DateRange::fromRequest($request);

        $contracts = Contract::query()
            ->select('contracts.*')
            ->selectRaw(
                'TIMESTAMPDIFF(MONTH, started_at, ended_at) + 1 AS months_run,
                 COALESCE((
                    SELECT SUM(amount) FROM contract_extensions
                    WHERE contract_extensions.contract_id = contracts.id
                 ), 0) AS extensions_amount_total'
            )
            ->whereIn('status', $statuses)
            ->whereNotNull('ended_at')
            ->whereBetween('ended_at', $range->bounds())
            ->when($request->filled('item_type_id'), fn (Builder $query) => $query->where('item_type_id', $request->integer('item_type_id')))
            ->with(['client', 'itemType'])
            ->orderByDesc('ended_at')
            ->get();

        return view($view, [
            'contracts' => $contracts,
            'range' => $range,
            'itemTypes' => ItemType::query()->orderBy('name')->get(),
        ]);
    }
}
