<?php

namespace App\Models\Contracts\Exceptions;

use Exception;

class ContractNotExistsException extends Exception
{
    public function __construct($message = 'El contrato que ingreso, no existe')
    {
        parent::__construct($message);
    }
}
