<?php  namespace SICV\Utils\Dates;

use DateInterval;

class DateDifference {

    public $dateInterval;
    public $intervals = ['y', 'm', 'd', 'h', 'i', 's'];

    public $translations = [
        'año',
        'mes',
        'día',
        'hora',
        'minuto',
        'segundo'
    ];

    function __construct(DateInterval $dateInterval) {
        $this->dateInterval = $dateInterval;
    }

    public function months(){
        return $this->dateInterval->m;
    }

    public function isFuture(){
        return $this->dateInterval->invert ? true : false;
    }

    public function isNow(){
        if(!$this->isToday()){
            return false;
        }
        if($this->dateInterval->h === 0 && $this->dateInterval->i === 0 && $this->dateInterval->s < 2){
            return true;
        }else{
            return false;
        }
    }

    public function isToday(){
        if($this->dateInterval->y === 0 && $this->dateInterval->m === 0 && $this->dateInterval->d === 0){
            return true;
        }else{
            return false;
        }
    }

    public function forHumans(){

        if ($this->isNow()) {
            return "Ahora mismo";
        }

        foreach ($this->intervals as $checkingUnit => $unit) {
            if ($this->{$unit} != 0) {
                $biggerUnit = $checkingUnit;
                break;
            }
        }

        $text = [];
        if($this->isFuture()){
            $text[] = 'en';
        }else{
            $text[] = 'hace';
        }

        $text[] = $this->getValueFromUnit($biggerUnit).' '.$this->pluralize($biggerUnit);

        if($biggerUnit = $this->nextUnit($biggerUnit)){
            $text[] = 'y '.$this->getValueFromUnit($biggerUnit).' '.$this->pluralize($biggerUnit);
        }

        return implode(' ', $text);

    }

    public function getValueFromUnit($unit){
        return $this->{$this->intervals[$unit]};
    }

    public function pluralize($unit){
        if($this->getValueFromUnit($unit) > 1){
            // Unico caso especial
            if($unit == 1){
                return "meses";
            }
            return $this->translations[$unit].'s';
        }else{
            return $this->translations[$unit];
        }

    }

    public function nextUnit($unit){
        if(isset($this->intervals[$unit + 1]) && $this->getValueFromUnit($unit + 1)){
            return $unit + 1;
        }
        return false;
    }

    function __get($name){
        if(method_exists($this, $name)){
            return $this->{$name}();
        }
        return $this->dateInterval->{$name};
    }

}