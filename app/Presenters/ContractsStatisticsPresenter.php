<?php

namespace App\Presenters;

use App\Models\Utils\Presenters\Presenter;

class ContractsStatisticsPresenter extends Presenter
{
    public function numberOfContracts()
    {
        return number_format($this->entity->numberOfContracts);
    }

    public function articles()
    {
        //TODO Refactor to a new class that handles this kind of things
        $return = ['<ul>'];
        $articles = $this->entity->articles();
        while ($articleNode = $this->entity->articleTypes->nextNode()) {
            if ($articleNode->getLevel() === 0) {
                $return[] = "<li><strong>{$articleNode->getData()->name()}</strong></li>";
            } elseif (array_key_exists($articleNode->id(), $articles)) {
                $return[] = "<li>{$articleNode->getData()->name()} ({$articles[$articleNode->id()]})</li>";
            }
        }
        $return[] = '</ul>';
        return implode("\n", $return);
    }

    public function goldWeight()
    {
        return $this->entity->goldWeight;
    }

    public function goldCount()
    {
        return $this->entity->goldCount;
    }

    public function totalAmount()
    {
        return $this->toMoney($this->entity->totalAmount);
    }

    public function totalDuedExtensions()
    {
        return $this->toMoney($this->entity->totalDuedExtensions);
    }

    public function totalPayedExtensions()
    {
        return $this->toMoney($this->entity->totalPayedExtensions);
    }

    public function totalPaymentMonth()
    {
        return $this->toMoney($this->entity->totalPaymentMonth);
    }

    public function profitPercentage()
    {
        return (round($this->entity->profitPercentage(), 2) + 0) . ' %';
    }
}
