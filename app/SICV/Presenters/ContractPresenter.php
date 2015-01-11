<?php  namespace SICV\Presenters;

use SICV\Utils\Presenters\Presenter;

class ContractPresenter extends Presenter {

    public function amount(){
        return '$ '.number_format($this->entity->amount());
    }

    public function payment(){
        return '$ '.number_format($this->entity->payment());
    }

    public function amountToTerminate(){
        return '$ '.number_format($this->entity->amountToTerminate());
    }

    public function totalExtensions(){
        return '$ '.number_format($this->entity->totalExtensions());
    }

    public function clientName(){
        return $this->entity->client->name();
    }

    public function elapsedMonths(){
        $difference = $this->entity->elapsedDifference();
        if($difference->months() != 0){
            return $difference->months() . (($difference->months() > 1) ? ' meses' : ' mes');
        }else{
            return $difference->forHumans();
        }
    }

    public function articlesNames(){
        $articles = $this->entity->articles;
        foreach($articles as $article){
            $articleNames[] = !empty($article->weight()) ? $article->description().' [' . ($article->weight() + 0) . 'g]' : $article->description();
        }
        return implode(', ', $articleNames);
    }

}