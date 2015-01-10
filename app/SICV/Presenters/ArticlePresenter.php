<?php  namespace SICV\Presenters;

use SICV\Utils\Presenters\Presenter;

class ArticlePresenter extends Presenter {

    public function getArticleType(){
        return $this->entity->articleType->article_type;
    }

}