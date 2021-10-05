<?php

namespace App\Models\Core\Commander;

interface CommandHandler
{
    public function handle($command);
}
