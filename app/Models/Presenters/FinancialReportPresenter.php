<?php

namespace App\Models\Presenters;

use App\Models\Utils\Presenters\Presenter;

class FinancialReportPresenter extends Presenter
{
    public function startDate()
    {
        return substr($this->entity->startDate, 0, 10);
    }

    public function endDate()
    {
        return substr($this->entity->endDate, 0, 10);
    }

    public function amountContracted()
    {
        return $this->toMoney($this->entity->amountContracted());
    }

    public function amountTerminated()
    {
        return $this->toMoney($this->entity->amountTerminated());
    }

    public function amountExtended()
    {
        return $this->toMoney($this->entity->amountExtended());
    }

    public function amountExpended()
    {
        return $this->toMoney($this->entity->amountExpended());
    }

    public function amountSold()
    {
        return $this->toMoney($this->entity->amountSold());
    }

    public function profitAmountTerminated()
    {
        return $this->toMoney($this->entity->profitAmountTerminated());
    }

    public function totalIncome()
    {
        return $this->toMoney($this->entity->totalIncome());
    }

    public function totalOutcome()
    {
        return $this->toMoney($this->entity->totalOutcome());
    }
}
