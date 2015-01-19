<?php


use SICV\Core\Commander\CommandBus;
use SICV\Reports\ReportRepository;

class ReportController extends BaseController {

    private $reportRepository;

    function __construct(ReportRepository $reportRepository, CommandBus $commandBus) {
        $this->reportRepository = $reportRepository;
        parent::__construct($commandBus);
    }

    public function expiredcontracts(){
        $contracts = $this->reportRepository->getExpiredContracts();
        return View::make('report.expiredcontracts', compact('contracts'));
    }

}
