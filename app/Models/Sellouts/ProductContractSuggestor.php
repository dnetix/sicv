<?php

namespace App\Models\Sellouts;

use App\Models\Articles\Article;
use App\Models\Contracts\Contract;
use App\Models\Utils\Presenters\PresentableTrait;

/**
 * Handles the buy and sell price suggestion for an article from a contract
 * when creating a sellout.
 */
class ProductContractSuggestor
{
    use PresentableTrait;

    protected $presenter = ProductContractSuggestorPresenter::class;

    /**
     * @var int Defines the identificator of the relationship between the contract and the article
     */
    public $pivot_id;
    /**
     * @var Article
     */
    public $article;

    public $contractId;

    public $contractAmount;
    public $sellPrice;
    public $buyPrice;

    public $numberOfArticlesFromContract;

    public function suggest(Contract $contract, Article $article)
    {
        $this->article = &$article;

        $this->pivot_id = $article->pivot->id;

        $this->contractId = $contract->id();
        $this->contractAmount = $contract->amount();
        $this->numberOfArticlesFromContract = $contract->articlesCount();

        $this->buyPrice = $article->pivot->article_amount;

        $this->sellPrice = ceil($contract->amountToTerminate() * ($this->buyPrice / $this->contractAmount()));

        return $this;
    }

    public function pivotId()
    {
        return $this->pivot_id;
    }

    public function contractId()
    {
        return $this->contractId;
    }

    public function contractAmount()
    {
        return $this->contractAmount;
    }

    public function articleId()
    {
        return $this->article->id();
    }

    public function articleDescription()
    {
        return $this->article->description();
    }

    public function sellPrice()
    {
        return $this->sellPrice;
    }

    public function buyPrice()
    {
        return $this->buyPrice;
    }
}
