<?php  namespace SICV\Contracts\Exceptions;

use Exception;

class ContractNotExistsException extends Exception {

    function __construct($message = "El contrato que ingreso, no existe") {
        parent::__construct($message);
    }

}