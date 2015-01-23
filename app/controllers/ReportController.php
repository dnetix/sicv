<?php


use SICV\Contracts\ContractRepository;
use SICV\Core\Commander\CommandBus;
use SICV\Reports\Actions\RetrievePreSelloutStatisticsCommand;

class ReportController extends BaseController {

    private $reportRepository;
    /**
     * @var ContractRepository
     */
    private $contractRepository;

    function __construct(ContractRepository $contractRepository, CommandBus $commandBus) {
        $this->contractRepository = $contractRepository;
        parent::__construct($commandBus);
    }

    public function presellouts(){

        $contracts = $this->contractRepository->getPreselloutContracts();

        $command = new RetrievePreSelloutStatisticsCommand($contracts);
        $data['contractStatistics'] = $this->execute($command);
        $data['contracts'] =& $contracts;
        $data['kindStatistics'] = 'presellouts';

        return View::make('report.presellout_contracts', $data);
    }

    public function expiredcontracts(){
        $contracts = $this->contractRepository->getExpiredContracts();
        return View::make('report.expired_contracts', compact('contracts'));
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
