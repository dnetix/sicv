<?php  namespace SICV\Presenters;

use SICV\Utils\Presenters\Presenter;

class FinancialReportPresenter extends Presenter {

    public function startDate(){
        return substr($this->entity->startDate, 0, 10);
    }

    public function endDate(){
        return substr($this->entity->endDate, 0, 10);
    }

    public function totalContractsAmount(){
        return $this->toMoney($this->entity->totalContractsAmount());
    }

    public function totalExtensions(){
        return $this->toMoney($this->entity->totalExtensions());
    }

    public function totalExpenses(){
        return $this->toMoney($this->entity->totalExpenses());
    }

    public function totalContractsTerminations(){
        return $this->toMoney($this->entity->totalContractsTerminations());
    }

    public function totalExtensionsFromEndAmounts(){
        return $this->toMoney($this->entity->totalExtensionsFromEndAmounts());
    }

}