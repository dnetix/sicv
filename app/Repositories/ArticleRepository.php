<?php

namespace App\Repositories;

use App\Models\Articles\Article;
use App\Models\Articles\ArticleType;
use Illuminate\Support\Collection;

class ArticleRepository
{
    public function getArticleTypeById($id): ArticleType
    {
        return ArticleType::findOrFail($id);
    }

    public function getArticleTypes(): Collection
    {
        return ArticleType::all();
    }

    public function saveArticleType(ArticleType &$articleType): ArticleType
    {
        $articleType->save();
        return $articleType;
    }

    public function getArticleById($id): Article
    {
        return Article::findOrFail($id);
    }

    public function storeArticle(Article $article): Article
    {
        $article->save();
        return $article;
    }
}
