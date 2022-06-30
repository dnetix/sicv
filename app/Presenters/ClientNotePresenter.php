<?php

namespace App\Presenters;

use App\Helpers\Dates\DateHelper;
use App\Models\Clients\ClientNote;
use App\Models\Utils\Presenters\Presenter;

/**
 * @property ClientNote $entity
 */
class ClientNotePresenter extends Presenter
{
    public function note(): string
    {
        return nl2br($this->entity->note());
    }

    public function createdAt(): string
    {
        return DateHelper::create($this->entity->createdAt())->toDifferenceWith()->forHumans();
    }

    public function user(): string
    {
        return $this->entity->user->name();
    }

    public function importance(): string
    {
        return $this->entity->importance();
    }

    public function class(): string
    {
        $class = [
            ClientNote::LEVEL_ALERT => 'bg-red-700 text-white',
            ClientNote::LEVEL_CRITICAL => 'bg-indigo-700 text-white',
            ClientNote::LEVEL_WARNING => 'bg-yellow-400 text-white',
            ClientNote::LEVEL_INFO => 'bg-gray-200 text-black',
        ];
        return $class[$this->entity->importance()];
    }
}
