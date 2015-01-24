<?php  namespace SICV\Presenters;

use SICV\Utils\Presenters\Presenter;

class ArticlePresenter extends Presenter {

    public function articleType(){
        return $this->entity->articleType->name();
    }

    public function name(){

    }

    public function articleAmount(){
        return $this->toMoney($this->entity->articleAmount());
    }

}