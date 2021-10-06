<?php

namespace App\Presenters;

use App\Models\Utils\Dates\DateHelper;
use App\Models\Utils\Presenters\Presenter;

class AnnulPresenter extends Presenter
{
    public function createdAt()
    {
        $dateHelper = DateHelper::create($this->entity->createdAt());
        return $dateHelper->translateToHumanDate() . ' [' . $dateHelper->translateToTime() . ']';
    }

    public function createdAtDifference()
    {
        return DateHelper::create($this->entity->createdAt())->toDifferenceWith()->forHumans();
    }

    public function originalAmount()
    {
        return $this->toMoney($this->entity->originalAmount());
    }

    public function userName()
    {
        return $this->entity->user->name();
    }
}
