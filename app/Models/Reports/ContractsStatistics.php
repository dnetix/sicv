<?php

namespace App\Models\Reports;

use App\Models\Articles\Article;
use App\Models\Articles\ArticleType;
use App\Models\Presenters\ContractsStatisticsPresenter;
use App\Models\Utils\Hierachical\CategoriesTree;
use App\Models\Utils\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Collection;

class ContractsStatistics
{
    use PresentableTrait;

    protected $presenter = ContractsStatisticsPresenter::class;

    public $numberOfContracts = 0;
    public $numberOfArticles = 0;

    public $articles;
    public $goldWeight = 0;
    public $goldCount = 0;

    public $totalAmount = 0;
    public $totalDuedExtensions = 0;
    public $totalPayedExtensions = 0;
    public $totalPaymentMonth = 0;

    /**
     * @var CategoriesTree
     */
    public $articleTypes;

    public function createStatistics(Collection $contracts)
    {
        $this->totalAmount = $contracts->sum('amount');
        $this->numberOfContracts = $contracts->count();

        foreach ($contracts as $contract) {
            $articles = $contract->articles;

            $this->totalDuedExtensions += $contract->duedExtensions();
            $this->totalPayedExtensions += $contract->payedExtensions();
            $this->totalPaymentMonth += $contract->payment();

            foreach ($articles as $article) {
                $this->addToArticles($article);
            }
        }

        return $this;
    }

    protected function addToArticles(Article $article)
    {
        $this->addArticleCount();
        if (!isset($this->articles[$article->articleTypeId()])) {
            $this->articles[$article->articleTypeId()] = 0;
        }
        $this->articles[$article->articleTypeId()] += 1;
        if (ArticleType::isGold($article->articleTypeId())) {
            $this->goldWeight += $article->weight();
            $this->goldCount += 1;
        }
    }

    public function profitPercentage()
    {
        if ($this->totalAmount() == 0) {
            return 0;
        } else {
            return ($this->totalPayedExtensions() / $this->totalAmount()) * 100;
        }
    }

    public function setArticleTypes($articleTypes)
    {
        $this->articleTypes = CategoriesTree::load($articleTypes);
    }

    /**
     * @return int
     */
    public function numberOfContracts()
    {
        return $this->numberOfContracts;
    }

    /**
     * @return int
     */
    public function numberOfArticles()
    {
        return $this->numberOfArticles;
    }

    /**
     * @return mixed
     */
    public function articles()
    {
        return $this->articles;
    }

    /**
     * @return int
     */
    public function goldWeight()
    {
        return $this->goldWeight;
    }

    /**
     * @return int
     */
    public function goldCount()
    {
        return $this->goldCount;
    }

    /**
     * @return int
     */
    public function totalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return int
     */
    public function totalDuedExtensions()
    {
        return $this->totalDuedExtensions;
    }

    /**
     * @return int
     */
    public function totalPayedExtensions()
    {
        return $this->totalPayedExtensions;
    }

    /**
     * @return int
     */
    public function totalPaymentMonth()
    {
        return $this->totalPaymentMonth;
    }

    private function addArticleCount()
    {
        $this->numberOfArticles++;
    }
}
