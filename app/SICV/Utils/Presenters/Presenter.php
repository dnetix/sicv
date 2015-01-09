<?php  namespace SICV\Utils\Presenters;

abstract class Presenter {

    protected $entity;

    function __construct($entity) {
        $this->entity = &$entity;
    }
    
    function __get($property){
        if(method_exists($this, $property)){
            return $this->{$property}();
        }
        return $this->entity->{$property};
    }

    function __call($name, $arguments){
        return $this->entity->{$name}($arguments);
    }

}