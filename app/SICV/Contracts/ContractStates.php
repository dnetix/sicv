<?php  namespace SICV\Contracts;

class ContractStates {

    const ACTIVE = 'active';
    const TERMINATED = 'terminated';
    const ANNULLED = 'annulled';

    public static $FORHUMAN = [
        self::ACTIVE => 'Activo',
        self::TERMINATED => 'Cancelado',
        self::ANNULLED => 'Anulado'
    ];

    public static function forHuman($state){
        return self::$FORHUMAN[$state];
    }

}