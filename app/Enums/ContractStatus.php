<?php

namespace App\Enums;

enum ContractStatus: int
{
    // Numeric values preserved from the legacy `estado` table.
    case Active = 1;
    case Redeemed = 2;
    case InStore = 3;
    case Sold = 4;
    case Scrapped = 5;
    case LegalHold = 6;
    case Voided = 7;

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Activo',
            self::Redeemed => 'Cliente Cancela',
            self::InStore => 'Para Almacén',
            self::Sold => 'Vendido',
            self::Scrapped => 'Chatarrizado',
            self::LegalHold => 'Problema Legal',
            self::Voided => 'Anulado',
        };
    }
}
