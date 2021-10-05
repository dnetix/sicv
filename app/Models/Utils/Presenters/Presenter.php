<?php

namespace App\Models\Utils\Presenters;

abstract class Presenter
{
    protected $entity;

    public function __construct(&$entity)
    {
        $this->entity = &$entity;
    }

    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return $this->{$property}();
        }
        return $this->entity->{$property};
    }

    public function __call($name, $arguments)
    {
        return $this->entity->{$name}($arguments);
    }

    public function toMoney($amount)
    {
        return '$ ' . number_format($amount);
    }
}
