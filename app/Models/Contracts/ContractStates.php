<?php

namespace App\Models\Contracts;

class ContractStates
{
    public const ACTIVE = 'active';
    /**
     * When a client pays and ends the contract.
     */
    public const TERMINATED = 'terminated';
    /**
     * When the client doesn't pay for it and it runs out of time SELLOUT.
     */
    public const ENDED = 'ended';
    public const ANNULLED = 'annulled';
    public const LEGALPROBLEM = 'legalprob';

    public static $FORHUMAN = [
        self::ACTIVE => 'Activo',
        self::TERMINATED => 'Cancelado',
        self::ENDED => 'Terminado',
        self::ANNULLED => 'Anulado',
        self::LEGALPROBLEM => 'Problema Legal',
    ];

    public static function forHuman($state)
    {
        return self::$FORHUMAN[$state];
    }
}
