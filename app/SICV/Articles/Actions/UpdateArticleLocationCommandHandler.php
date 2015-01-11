<?php  namespace SICV\Articles\Actions;

use SICV\Articles\ArticleRepository;
use SICV\Core\Commander\CommandHandler;

class UpdateArticleLocationCommandHandler implements CommandHandler {


    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    function __construct(ArticleRepository $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function handle($command) {
        $article = $this->getArticleById($command);
        $this->fillArticleFields($article, $command);
        $this->updateArticle($article);
        return $article;
    }

    public function getArticleById($command) {
        return $this->articleRepository->getArticleById($command->article_id);
    }

    private function fillArticleFields(&$article, $command) {
        $article->location = $command->location;
    }

    private function updateArticle(&$article) {
        $this->articleRepository->update($article);
    }

}