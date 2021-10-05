<?php

namespace App\Models\Articles\Actions;

class UpdateArticleLocationCommand
{
    public $article_id;
    public $location;

    public function __construct($article_id, $location)
    {
        $this->article_id = $article_id;
        $this->location = strtoupper($location);
    }
}
