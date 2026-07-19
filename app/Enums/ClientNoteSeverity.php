<?php

namespace App\Enums;

enum ClientNoteSeverity: string
{
    case Warning = 'warning';
    case Alert = 'alert';

    public function label(): string
    {
        return match ($this) {
            self::Warning => 'Aviso',
            self::Alert => 'Alerta',
        };
    }
}
