<?php

namespace App\Presenters;

use App\Models\Utils\Presenters\Presenter;

class ClientPresenter extends Presenter
{
    public function name()
    {
        return $this->entity->name() . ($this->entity->isFlagged() ? ' <span class="label label-danger">MARCADO</span>' : '');
    }

    public function mobile()
    {
        $phones[] = $this->entity->phoneNumber();
        if (!empty($this->entity->mobile())) {
            $phones[] = $this->entity->mobile();
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
