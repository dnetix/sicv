<?php  namespace SICV\Presenters;

use SICV\Utils\Presenters\Presenter;

class ContractPresenter extends Presenter {

    public function getAmount(){
        return '$ '.number_format($this->entity->getAmount());
    }

    public function getPayment(){
        return '$ '.number_format($this->entity->getPayment());
    }

    public function getClientName(){
        return $this->entity->client->getName();
    }

    public function getElapsedMonths(){
        $difference = $this->entity->getElapsedDifference();
        if($difference->months() != 0){
            return $difference->months() . (($difference->months() > 1) ? ' meses' : ' mes');
        }else{
            return $difference->forHumans();
        }
    }

    public function getArticlesNames(){
        $articles = $this->entity->articles;
        foreach($articles as $article){
            $articleNames[] = !empty($article->getWeight()) ? $article->getDescription().' [' . ($article->getWeight() + 0) . 'g]' : $article->getDescription();
        }
        return implode(', ', $articleNames);
    }

}