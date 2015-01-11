<?php  namespace SICV\Contracts;

class ContractStates {

    const ACTIVE = 'active';

    public static $FORHUMAN = [
        self::ACTIVE => 'Activo'
    ];

    public static function forHuman($state){
        return self::$FORHUMAN[$state];
    }

}