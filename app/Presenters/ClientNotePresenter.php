<?php

namespace App\Presenters;

use App\Models\Clients\ClientNote;
use App\Helpers\Dates\DateHelper;
use App\Models\Utils\Presenters\Presenter;

class ClientNotePresenter extends Presenter
{
    public function author()
    {
        return $this->entity->user->name();
    }

    public function createdAt()
    {
        return DateHelper::create($this->entity->createdAt())->toDifferenceWith()->forHumans();
    }

    public function note()
    {
        return nl2br($this->entity->note());
    }

    public function importance()
    {
        return $this->entity->importance();
    }

    public static function getSelectOptions()
    {
        return [
            ClientNote::NI_LOW => 'Nota de Informaci&oacute;n',
            ClientNote::NI_MEDIUM => 'Nota Importante',
            ClientNote::NI_HIGH => 'Nota Prioritaria',
        ];
    }
}
