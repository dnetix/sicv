<?php

namespace App\Models\Core\Commander\Eventing;

trait EventGenerator
{
    protected $pendingEvents = [];

    public function raise($event)
    {
        $this->pendingEvents[] = $event;
    }

    public function releaseEvents()
    {
        $events = $this->pendingEvents;
        $this->pendingEvents = [];
        return $events;
    }
}
