<?php

namespace App\Models\Utils\Presenters;

trait PresentableTrait
{
    protected $presenterInstance;

    public function present()
    {
        if (!$this->presenter || !class_exists($this->presenter)) {
            throw new PresenterException('No se ha declarado una clase para el presenter o no existe');
        }
        if (!isset($this->presenterInstance)) {
            $this->presenterInstance = new $this->presenter($this);
        }
        return $this->presenterInstance;
    }
}
