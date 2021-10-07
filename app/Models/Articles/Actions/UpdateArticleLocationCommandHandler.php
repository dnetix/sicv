<?php

namespace App\Models\Articles\Actions;

use App\Models\Core\Commander\CommandHandler;
use App\Repositories\ArticleRepository;

class UpdateArticleLocationCommandHandler implements CommandHandler
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
        $article = $this->getArticleById($command);
        $this->fillArticleFields($article, $command);
        $this->updateArticle($article);
        return $article;
    }

    public function getArticleById($command)
    {
        return $this->articleRepository->getArticleById($command->article_id);
    }

    private function fillArticleFields(&$article, $command)
    {
        $article->location = $command->location;
    }

    private function updateArticle(&$article)
    {
        $this->articleRepository->update($article);
    }
}
