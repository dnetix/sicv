<?php

namespace App\Models\Core\Commander;

use Illuminate\Foundation\Application;

class ValidationCommandBus implements CommandBus
{
    /**
     * @var DefaultCommandBus
     */
    protected $commandBus;
    /**
     * @var Application
     */
    protected $app;
    protected $commandTranslator;

    public function __construct(DefaultCommandBus $commandBus, Application $app, CommandTranslator $commandTranslator)
    {
        $this->commandBus = $commandBus;
        $this->app = $app;
        $this->commandTranslator = $commandTranslator;
    }

    public function execute($command)
    {
        $validator = $this->commandTranslator->toValidator($command);

        if (class_exists($validator)) {
            $this->app->make($validator)->validate($command);
        }

        return $this->commandBus->execute($command);
    }
}
