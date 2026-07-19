<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\CompanySetting;
use App\Models\ExpenseType;
use App\Models\ItemType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Baseline data for a fresh development install. A real environment is
     * populated from production data via `php artisan legacy:import`.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrador',
                'role' => UserRole::Administrator,
                'active' => true,
                'password' => 'secret',
            ],
        );

        // Ids preserved from the legacy catalog: id 2 (Oro) is special-cased
        // by the gold business rules.
        $itemTypes = [
            1 => 'Sin definir', 2 => 'Oro', 3 => 'Bicicleta', 4 => 'Televisor',
            5 => 'Portatil', 6 => 'Computador', 7 => 'Licuadora', 8 => 'Camara',
            9 => 'Celular', 10 => 'DVD', 11 => 'Moto', 12 => 'Herramientas',
            13 => 'Equipo Sonido', 14 => 'Lavadora', 15 => 'Consola Juegos',
        ];

        foreach ($itemTypes as $id => $name) {
            ItemType::ensure($id, $name);
        }

        $expenseTypes = [
            'Salario', 'Arriendo', 'Servicios Publicos', 'Implementos De Oficina',
            'Papeleria', 'Publicidad', 'Adecuacion Del Local', 'Varios',
        ];

        foreach ($expenseTypes as $name) {
            ExpenseType::query()->firstOrCreate(['name' => $name]);
        }

        CompanySetting::query()->firstOr(fn () => CompanySetting::query()->create([
            'legal_name' => 'Compraventa S.A.S.',
            'tax_id' => '000000000-0',
            'name' => 'Compraventa',
            'address' => 'Calle 1 # 1-1',
            'phone' => '000 000 0000',
            'city' => 'Ciudad',
        ]));
    }
}
