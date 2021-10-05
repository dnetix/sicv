<?php

namespace App\Models\Core\Commander\Eventing;

use Illuminate\Events\Dispatcher;
use Illuminate\Log\Writer;

class EventDispatcher
{
    protected $dispatcher;
    /**
     * @var Writer
     */
    private $log;

    public function __construct(Dispatcher $dispatcher, Writer $log)
    {
        $this->dispatcher = $dispatcher;
        $this->log = $log;
    }

    public function dispatch(array $events)
    {
        foreach ($events as $event) {
            $eventName = $this->resolveEventName($event);
            $this->fireEvent($eventName, $event);
            $this->logEventFired($eventName);
        }
    }

    protected function resolveEventName($event)
    {
        return str_replace('\\', '.', get_class($event));
    }

    /**
     * @param $eventName
     * @param $event
     */
    public function fireEvent($eventName, $event)
    {
        $this->dispatcher->fire($eventName, $event);
    }

    /**
     * @param $eventName
     */
    public function logEventFired($eventName)
    {
        $this->log->info("Eventing: {$eventName} was fired.");
    }
}
