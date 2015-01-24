<?php  namespace SICV\Sellouts;

use SICV\Utils\Presenters\Presenter;

class ProductContractSuggestorPresenter extends Presenter {

    public function sellPrice(){
        return $this->toMoney($this->entity->sellPrice());
    }

    public function buyPrice(){
        return $this->toMoney($this->entity->buyPrice());
    }

    public function contractAmount(){
        return $this->toMoney($this->entity->contractAmount());
    }

}