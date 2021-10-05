<?php

namespace App\Models\Articles;

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

    public function getArticleTypes()
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
