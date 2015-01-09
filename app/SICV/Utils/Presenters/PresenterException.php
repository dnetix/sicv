<?php  namespace SICV\Utils\Presenters;

use Exception;

class PresenterException extends Exception {

    function __construct($message) {
        parent::__construct($message);
    }

}