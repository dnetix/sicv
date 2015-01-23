<?php  namespace SICV\Reports\Actions;

use SICV\Articles\ArticleRepository;
use SICV\Core\Commander\CommandHandler;
use SICV\Reports\ContractsStatistics;

class RetrievePreSelloutStatisticsCommandHandler implements CommandHandler {

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    function __construct(ArticleRepository $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function handle($command) {

        $contractStatistics = (new ContractsStatistics())->createStatistics($command->contracts);
        $contractStatistics->articleTypes = $this->getArticleTypesAsLineageTree();
        return $contractStatistics;

    }

    public function getArticleTypesAsLineageTree(){
        return $this->articleRepository->getArticleTypesAsLineageTree();
    }

}