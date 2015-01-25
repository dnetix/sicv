<?php  namespace SICV\Contracts;

class ContractStates {

    const ACTIVE = 'active';
    /**
     * When a client pays and ends the contract
     */
    const TERMINATED = 'terminated';
    /**
     * When the client doesn't pay for it and it runs out of time SELLOUT
     */
    const ENDED = 'ended';
    const ANNULLED = 'annulled';

    public static $FORHUMAN = [
        self::ACTIVE => 'Activo',
        self::TERMINATED => 'Cancelado',
        self::ENDED => 'Terminado',
        self::ANNULLED => 'Anulado'
    ];

    public static function forHuman($state){
        return self::$FORHUMAN[$state];
    }

}