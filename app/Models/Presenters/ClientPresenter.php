<?php

namespace App\Models\Presenters;

use App\Models\Utils\Presenters\Presenter;

class ClientPresenter extends Presenter
{
    public function name()
    {
        return $this->entity->name() . ($this->entity->isFlagged() ? ' <span class="label label-danger">MARCADO</span>' : '');
    }

    public function address()
    {
        return '<i class="fa fa-map-marker"></i> ' . $this->entity->address();
    }

    public function phones()
    {
        $phones[] = '<i class="fa fa-phone"></i> ' . $this->entity->phoneNumber();
        if (!empty($this->entity->cellNumber())) {
            $phones[] = '<i class="fa fa-mobile-phone"></i> ' . $this->entity->cellNumber();
        }
        return implode(' ', $phones);
    }

    public function identification()
    {
        return '<i class="fa fa-user"></i> ' . $this->entity->idType() . ' <b>' . $this->entity->idNumber() . '</b>';
    }

    public function idExpedition()
    {
        return $this->entity->idExpedition();
    }
}
