<?php  namespace SICV\Reports;

use SICV\Budgets\Expense;
use SICV\Contracts\Contract;
use SICV\Contracts\Extension;
use SICV\Presenters\FinancialReportPresenter;
use SICV\Utils\Dates\DateHelper;
use SICV\Utils\Presenters\PresentableTrait;

class FinancialReport {

    protected $presenter = FinancialReportPresenter::class;
    use PresentableTrait;

    public $startDate;
    public $endDate;

    public $contracts;
    public $contractsTerminated;
    public $extensions;
    public $expenses;

    private $endAmountContractsTerminated;

    function __construct($startDate, $endDate = null) {
        $this->startDate = DateHelper::create($startDate)->toSQLReport();
        $this->endDate = DateHelper::create($endDate)->toSQLReport(true);
    }

    public function calculate(){
        $this->contracts = Contract::whereBetween('created_at', [$this->startDate(), $this->endDate()]);
        $this->contractsTerminated = Contract::whereBetween('end_date', [$this->startDate(), $this->endDate()]);
        $this->extensions = Extension::whereBetween('created_at', [$this->startDate(), $this->endDate()]);
        $this->expenses = Expense::whereBetween('created_at', [$this->startDate(), $this->endDate()]);
        return $this;
    }

    public function startDate(){
        return $this->startDate;
    }

    public function endDate(){
        return $this->endDate;
    }

    public function totalContractsAmount(){
        return $this->contracts->sum('amount');
    }

    public function totalContractsTerminations(){
        $this->endAmountContractsTerminated = $this->contractsTerminated->sum('end_amount');
        return $this->endAmountContractsTerminated;
    }

    public function totalExtensions(){
        return $this->extensions->sum('amount');
    }

    public function totalExpenses(){
        return $this->expenses->sum('amount');
    }

    public function totalExtensionsFromEndAmounts(){
        if(is_null($this->endAmountContractsTerminated)){
            $this->totalContractsTerminations();
        }
        return $this->endAmountContractsTerminated - $this->contractsTerminated->sum('amount');
    }


}