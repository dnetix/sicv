<?php


use SICV\Contracts\Contract;
use SICV\Contracts\ContractRepository;
use SICV\Contracts\Extension;
use SICV\Core\Commander\CommandBus;
use SICV\Reports\Actions\RetrievePreSelloutStatisticsCommand;
use SICV\Reports\FinancialReport;

class ReportController extends BaseController {

    /**
     * @var ContractRepository
     */
    private $contractRepository;

    function __construct(ContractRepository $contractRepository, CommandBus $commandBus) {
        $this->contractRepository = $contractRepository;
        parent::__construct($commandBus);
    }

    public function financial(){

        if(Input::has('startDate')){
            $startDate = Input::get('startDate');
            $endDate = Input::get('endDate');
        }else{
            $startDate = Date::create()->toSQLDate();
            $endDate = Date::create()->toSQLDate();
        }

        $financialReport = (new FinancialReport($startDate, $endDate))
            ->calculate();

        $data['financial'] = $financialReport;

        return View::make('report.financial', $data);
    }

    public function expiredcontracts(){
        $contracts = $this->contractRepository->getExpiredContracts();

        $command = new RetrievePreSelloutStatisticsCommand($contracts);
        $data['contractStatistics'] = $this->execute($command);

        $data['contracts'] =& $contracts;

        return View::make('report.expired_contracts', $data);
    }

    public function contractstatistics($kind){
        if($kind == 'presellouts'){
            $contracts = $this->contractRepository->getPreselloutContracts();
        }
        $command = new RetrievePreSelloutStatisticsCommand($contracts);
        $data['contractStatistics'] = $this->execute($command);
        $data['kindStatistics'] = $kind;
        return View::make('report.partials._contracts_statistics', $data);
    }

}
