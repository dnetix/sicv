<?php

namespace App\Repositories;

use App\Models\Articles\Article;
use App\Models\Articles\ArticleType;
use Illuminate\Support\Collection;

class ArticleRepository
{
    /**
     * @param $id
     * @return ArticleType
     */
    public function getArticleTypeById($id)
    {
        return ArticleType::findOrFail($id);
    }

    public function getArticleTypes(): Collection
    {
        return ArticleType::all();
    }

    public function saveArticleType(ArticleType &$articleType)
    {
        $articleType->save();
    }

    public function getArticleById($id)
    {
        return Article::findOrFail($id);
    }

    public function create(Article &$article)
    {
        return $article->save();
    }

    public function update(Article $article)
    {
        return $article->save();
    }
}
