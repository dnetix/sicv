<?php

namespace App\Models\Core\Commander\Eventing;

use Illuminate\Log\Writer;

class EventListener
{
    protected $log;

    public function __construct(Writer $log)
    {
        $this->log = $log;
    }

    public function handle($event)
    {
        $method = $this->resolveMethodName($event);

        if ($this->listenerIsRegistered($method)) {
            $this->logListenerTriggered($method);
            return $this->$method($event);
        }
    }

    /**
     * @param $event
     * @return string
     */
    public function resolveMethodName($event)
    {
        return 'when' . (new \ReflectionClass($event))->getShortName();
    }

    /**
     * @param $method
     * @return bool
     */
    public function listenerIsRegistered($method)
    {
        return method_exists($this, $method);
    }

    /**
     * @param $method
     */
    public function logListenerTriggered($method)
    {
        $this->log->info("Eventing: listener {$method} was triggered");
    }
}
