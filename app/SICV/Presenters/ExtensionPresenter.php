<?php  namespace SICV\Presenters;

use SICV\Utils\Dates\DateHelper;
use SICV\Utils\Presenters\Presenter;

class ExtensionPresenter extends Presenter {

    public function amount(){
        return $this->toMoney($this->entity->amount());
    }

    public function createdAt(){
        return DateHelper::toYearMonth($this->entity->createdAt());
    }

}