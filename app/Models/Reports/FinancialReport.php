<?php

namespace App\Models\Reports;

use App\Models\Budgets\Expense;
use App\Models\Contracts\Contract;
use App\Models\Contracts\Extension;
use App\Presenters\FinancialReportPresenter;
use App\Models\Sales\Invoice;
use App\Models\Utils\Dates\DateHelper;
use App\Models\Utils\Presenters\PresentableTrait;

class FinancialReport
{
    use PresentableTrait;

    protected $presenter = FinancialReportPresenter::class;

    public $startDate;
    public $endDate;

    public $contracts;
    public $contractsTerminated;
    public $extensions;
    public $expenses;
    public $invoices;

    // Saves the results to avoid the need of making the queries twice
    private $cached = [];

    private $endAmountContractsTerminated;

    public function __construct($startDate, $endDate = null)
    {
        $this->startDate = DateHelper::create($startDate)->toSQLReport();
        $this->endDate = DateHelper::create($endDate)->toSQLReport(true);
    }

    public function calculate()
    {
        //TODO Extract functions to their own repositories
        $this->contracts = Contract::whereBetween('created_at', [$this->startDate(), $this->endDate()]);
        $this->contractsTerminated = Contract::whereBetween('end_date', [$this->startDate(), $this->endDate()]);
        $this->extensions = Extension::whereBetween('created_at', [$this->startDate(), $this->endDate()]);
        $this->expenses = Expense::whereBetween('created_at', [$this->startDate(), $this->endDate()]);
        $this->invoices = Invoice::whereBetween('created_at', [$this->startDate(), $this->endDate()]);

        $this->cached['contracted'] = $this->contracts->sum('amount');
        $this->cached['terminatedAmount'] = $this->contractsTerminated->sum('amount');
        $this->cached['terminatedEndAmount'] = $this->contractsTerminated->sum('end_amount');
        $this->cached['extended'] = $this->extensions->sum('amount');
        $this->cached['expended'] = $this->expenses->sum('amount');
        $this->cached['sold'] = $this->invoices->sum('amount');

        return $this;
    }

    public function startDate()
    {
        return $this->startDate;
    }

    public function endDate()
    {
        return $this->endDate;
    }

    public function amountContracted()
    {
        return $this->cached['contracted'];
    }

    public function amountTerminated()
    {
        return $this->cached['terminatedEndAmount'];
    }

    public function amountExtended()
    {
        return $this->cached['extended'];
    }

    public function amountExpended()
    {
        return $this->cached['expended'];
    }

    public function amountSold()
    {
        return $this->cached['sold'];
    }

    public function profitAmountTerminated()
    {
        return $this->amountTerminated() - $this->cached['terminatedAmount'];
    }

    public function totalIncome()
    {
        return $this->amountTerminated() + $this->amountExtended() + $this->amountSold();
    }

    public function totalOutcome()
    {
        return $this->amountContracted() + $this->amountExpended();
    }
}
