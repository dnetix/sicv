<?php

namespace App\Models\Presenters;

use App\Models\Utils\Dates\DateHelper;
use App\Models\Utils\Presenters\Presenter;

class ExtensionPresenter extends Presenter
{
    public function amount()
    {
        return $this->toMoney($this->entity->amount());
    }

    public function createdAt()
    {
        return DateHelper::toYearMonth($this->entity->createdAt());
    }
}
