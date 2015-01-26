<?php  namespace SICV\Reports;

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

    function __construct($startDate, $endDate = null) {
        $this->startDate = DateHelper::create($startDate)->toSQLReport();
        $this->endDate = DateHelper::create($endDate)->toSQLReport(true);
    }

    public function calculate(){
        $this->contracts = Contract::whereBetween('created_at', [$this->startDate(), $this->endDate()]);
        $this->contractsTerminated = Contract::whereBetween('end_date', [$this->startDate(), $this->endDate()]);
        $this->extensions = Extension::whereBetween('created_at', [$this->startDate(), $this->endDate()]);
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
        return $this->contractsTerminated->sum('end_amount');
    }

    public function totalExtensions(){
        return $this->extensions->sum('amount');
    }

}