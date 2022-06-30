<?php

namespace App\Presenters;

use App\Helpers\Dates\DateHelper;
use App\Helpers\RepositoryHelper;
use App\Models\Contracts\Contract;
use App\Models\Utils\Presenters\Presenter;

/**
 * @property Contract $entity
 */
class ContractPresenter extends Presenter
{
    public $lastExtension;

    public function clientName(): string
    {
        return $this->entity->getClient()->name();
    }

    public function amount()
    {
        return $this->toMoney($this->entity->amount());
    }

    public function endAmount()
    {
        return $this->toMoney($this->entity->endAmount());
    }

    public function extension(): string
    {
        return $this->toMoney($this->entity->extension());
    }

    public function percentage()
    {
        return ($this->entity->percentage() + 0) . '%';
    }

    public function amountToTerminate()
    {
        return $this->toMoney($this->entity->amountToTerminate());
    }

    public function payedExtensions()
    {
        return $this->toMoney($this->entity->payedExtensions());
    }

    public function endDate()
    {
        return DateHelper::create($this->entity->endDate())->translateToHumanDate();
    }

    public function state()
    {
        return trans('contract.' . $this->entity->state());
    }

    public function date()
    {
        $dateHelper = DateHelper::create($this->entity->createdAt());
        return $dateHelper->translateToHumanDate() . ' ' . $dateHelper->translateToTime();
    }

    public function articlesNames(): string
    {
        $articles = RepositoryHelper::forContracts()->getContractArticles($this->entity);
        foreach ($articles as $article) {
            $articleNames[] = !empty($article->weight()) ? $article->description() . ' [' . ($article->weight() + 0) . 'g]' : $article->description();
        }
        return implode(', ', $articleNames);
    }

    public function profit()
    {
        return $this->toMoney($this->entity->profit()) . ' (' . ($this->entity->profitPercent() + 0) . '%)';
    }
}
