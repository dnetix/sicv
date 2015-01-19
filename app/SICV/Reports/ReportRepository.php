<?php  namespace SICV\Reports;

use SICV\Articles\ArticleRepository;
use SICV\Clients\ClientRepository;
use SICV\Contracts\ContractRepository;

class ReportRepository {

    private $contractRepository;
    private $clientRepository;
    private $articleRepository;

    function __construct(ContractRepository $contractRepository, ClientRepository $clientRepository, ArticleRepository $articleRepository) {
        $this->contractRepository = $contractRepository;
        $this->articleRepository = $articleRepository;
        $this->clientRepository = $clientRepository;
    }

    public function getExpiredContracts() {

        return $this->contractRepository->getExpiredContracts();

    }

}