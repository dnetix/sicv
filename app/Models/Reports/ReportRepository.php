<?php

namespace App\Models\Reports;

use App\Models\Articles\ArticleRepository;
use App\Models\Clients\ClientRepository;
use App\Repositories\ContractRepository;

class ReportRepository
{
    private $contractRepository;
    private $clientRepository;
    private $articleRepository;

    public function __construct(ContractRepository $contractRepository, ClientRepository $clientRepository, ArticleRepository $articleRepository)
    {
        $this->contractRepository = $contractRepository;
        $this->articleRepository = $articleRepository;
        $this->clientRepository = $clientRepository;
    }

    public function getExpiredContracts()
    {
        return $this->contractRepository->getExpiredContracts();
    }
}
