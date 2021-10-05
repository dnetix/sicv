<?php

namespace App\Models\Presenters;

use App\Models\Utils\Dates\DateHelper;
use App\Models\Utils\Presenters\Presenter;

class ExpensePresenter extends Presenter
{
    public function createdAt()
    {
        return DateHelper::create($this->entity->createdAt())->translateToShortDate();
    }

    public function amount()
    {
        return $this->toMoney($this->entity->amount());
    }

    public function description()
    {
        return $this->entity->description();
    }

    public function user()
    {
        return $this->entity->user->name();
    }

    public function expenseType()
    {
        return $this->entity->expenseType->name();
    }
}
