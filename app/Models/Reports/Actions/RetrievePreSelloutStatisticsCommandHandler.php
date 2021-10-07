<?php

namespace App\Models\Reports\Actions;

use App\Models\Core\Commander\CommandHandler;
use App\Models\Reports\ContractsStatistics;
use App\Repositories\ArticleRepository;

class RetrievePreSelloutStatisticsCommandHandler implements CommandHandler
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function handle($command)
    {
        $contractStatistics = (new ContractsStatistics())->createStatistics($command->contracts);
        $contractStatistics->setArticleTypes($this->getArticleTypes());

        return $contractStatistics;
    }

    public function getArticleTypes()
    {
        return $this->articleRepository->getArticleTypes();
    }
}
