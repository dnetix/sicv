<?php  namespace SICV\Presenters;

use SICV\Contracts\ContractStates;
use SICV\Utils\Dates\DateHelper;
use SICV\Utils\Presenters\Presenter;

class ContractPresenter extends Presenter {

    public $dueDateHelper;
    public $lastExtension;

    public function amount(){
        return $this->toMoney($this->entity->amount());
    }

    public function endAmount(){
        return $this->toMoney($this->entity->endAmount());
    }

    public function payment(){
        return $this->toMoney($this->entity->payment());
    }

    public function percentage(){
        return ($this->entity->percentage() + 0).'%';
    }

    public function amountToTerminate(){
        return $this->toMoney($this->entity->amountToTerminate());
    }

    public function payedExtensions(){
        return $this->toMoney($this->entity->payedExtensions());
    }

    public function dueDate(){
        return DateHelper::create($this->entity->createdAt())->changeMonths($this->entity->contractMonths())->translateToHumanDate();
    }

    public function dueDateDifference(){
        return DateHelper::create($this->entity->createdAt())->changeMonths($this->entity->contractMonths())->toDifferenceWith()->forHumans();
    }

    public function endDate(){
        return DateHelper::create($this->entity->endDate())->translateToHumanDate();
    }

    public function endDateDifference(){
        return DateHelper::create($this->entity->endDate())->toDifferenceWith()->forHumans();
    }

    public function duedExtensions(){
        return $this->toMoney($this->entity->duedExtensions());
    }

    public function clientName(){
        return $this->entity->client->name();
    }

    public function clientContactInfo(){
        return $this->entity->client->present()->phones();
    }

    public function state(){
        return ContractStates::$FORHUMAN[$this->entity->state()];
    }

    public function createdAt(){
        $dateHelper = DateHelper::create($this->entity->createdAt());
        return $dateHelper->translateToHumanDate().' ['.$dateHelper->translateToTime().']';
    }

    public function shortCreatedAt(){
        return DateHelper::create($this->entity->createdAt())->translateToShortDate();
    }

    public function elapsedSinceCreated(){
        return DateHelper::getDifference($this->entity->createdAt())->forHumans();
    }

    public function elapsedMonths(){
        $difference = $this->entity->elapsedDifference();
        if($difference->inMonths() != 0){
            return $difference->inMonths() . (($difference->inMonths() > 1) ? ' meses' : ' mes');
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

    public function lastExtensionDate(){
        $lastExtension = $this->entity->lastExtension();
        if(is_null($lastExtension)){
            return '';
        }
        return DateHelper::create($lastExtension->createdAt())->translateToShortDate();
    }

    public function lastExtensionDateDiff(){
        $lastExtension = $this->entity->lastExtension();
        if(is_null($lastExtension)){
            return '';
        }
        return DateHelper::getDifference($lastExtension->createdAt())->forHumans();
    }

    /**
     * Presents a good looking view for months statistics
     */
    public function monthStatistics($small = false){
        //TODO Refactor and find right place
        $monthsElapsed = $this->entity->elapsedMonths();
        $monthsExtended = $this->entity->extendedMonths();
        $months = $this->entity->months();

        // First shows the transcurred months
        $statistics[] = ['class' => 'default', 'months' => $monthsElapsed];
        // number of months of the contract
        $statistics[] = ['class' => 'default', 'months' => $months];

        // Look for the appropiate color clasification for the extended months
        $parameter = ($monthsExtended + 1) - $monthsElapsed;
        if($parameter < 0){
            $class = 'danger';
        }else if($parameter > 1){
            $class = 'success';
        }else{
            $class = 'warning';
        }
        $statistics[] = ['class' => $class, 'months' => $monthsExtended];

        // Look for the appropiate color clasification for the remaining
        $parameter = ($months + $monthsExtended) - $monthsElapsed;
        if($parameter <= 0){
            $class = 'danger';
        }else if($parameter > ceil($months * 0.5)){
            $class = 'success';
        }else{
            $class = 'warning';
        }
        $statistics[] = ['class' => $class, 'months' => $parameter];

        if($small){
            $btnType = 'btn-sm';
            $btnIcon = '';
        }else{
            $btnType = 'btn-lg';
            $btnIcon = '<i class="fa fa-calendar-o"></i> ';
        }

        $return = '';
        $tooltips = [
            'Meses transcurridos',
            'Meses del contrato',
            'Meses abonados',
            'Meses restantes'
        ];
        foreach($statistics as $index => $data){
            $return .= '<span data-toggle="tooltip" data-original-title="'.$tooltips[$index].'" class="tooltips btn '.$btnType.' btn-'.$data['class'].'">'.$btnIcon.$data['months']."</span>\n";
        }

        return $return;
    }

    public function profit(){
        return $this->toMoney($this->entity->profit()).' ('.($this->entity->profitPercent() + 0).'%)';
    }

}