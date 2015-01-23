<?php  namespace SICV\Presenters;

use SICV\Utils\Presenters\Presenter;

class ArticlePresenter extends Presenter {

    public function articleType(){
        return $this->entity->articleType->name();
    }

}