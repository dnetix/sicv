<?php  namespace SICV\Presenters;

use SICV\Utils\Presenters\Presenter;

class ContractPresenter extends Presenter {

    public function getAmount(){
        return '$ '.number_format($this->entity->getAmount());
    }

    public function getClientName(){
        return $this->entity->client->getName();
    }

    public function getArticlesNames(){
        $articles = $this->entity->articles;
        foreach($articles as $article){
            $articleNames[] = !empty($article->getWeight()) ? $article->getDescription().' [' . ($article->getWeight() + 0) . 'g]' : $article->getDescription();
        }
        return implode(', ', $articleNames);
    }

}