<?php

namespace App\Enums;

enum UserRole: int
{
    // Numeric values preserved from the legacy system, where authorization
    // was a simple "role >= level" check (3 = operator, 6 = administrator).
    case Employee = 3;
    case SeniorEmployee = 4;
    case Administrator = 6;

    public function label(): string
    {
        return match ($this) {
            self::Employee => 'Empleado',
            self::SeniorEmployee => 'Empleado +',
            self::Administrator => 'Administrador',
        };
    }

    public function atLeast(self $role): bool
    {
        return $this->value >= $role->value;
    }
}
