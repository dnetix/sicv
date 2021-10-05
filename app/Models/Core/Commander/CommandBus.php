<?php

namespace App\Models\Core\Commander;

interface CommandBus
{
    public function execute($command);
}
