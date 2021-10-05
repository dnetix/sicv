<?php

namespace App\Models\Users\Exceptions;

use Exception;

class UnauthorizedUserAction extends Exception
{
    public function __construct($message = 'No tiene permisos para realizar esta accion')
    {
        parent::__construct($message);
    }
}
