<?php

namespace App\Models\Core\Commander;

use Illuminate\Foundation\Application;

class DefaultCommandBus implements CommandBus
{
    /**
     * @var Application
     */
    protected $app;
    protected $commandTranslator;

    public function __construct(Application $app, CommandTranslator $commandTranslator)
    {
        $this->app = $app;
        $this->commandTranslator = $commandTranslator;
    }

    public function execute($command)
    {
        $handler = $this->translateToCommandHandler($command);
        return $this->resolveOutOfTheIOC($handler)->handle($command);
    }

    protected function resolveOutOfTheIOC($handler)
    {
        return $this->app->make($handler);
    }

    protected function translateToCommandHandler($command)
    {
        return $this->commandTranslator->toCommandHandler($command);
    }
}
