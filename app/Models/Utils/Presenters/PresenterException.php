<?php

namespace App\Models\Utils\Presenters;

use Exception;

class PresenterException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
