<?php

namespace App\Presenters;

use App\Models\Utils\Presenters\Presenter;

class ArticlePresenter extends Presenter
{
    public function articleType()
    {
        return $this->entity->articleType->name();
    }

    public function description()
    {
        return $this->entity->description() . ($this->entity->isGold() ? " [{$this->entity->weight()} g]" : '');
    }

    public function articleAmount()
    {
        return $this->toMoney($this->entity->articleAmount());
    }
}
