<?php

namespace Database\Seeders;

use App\Models\Budgets\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseTypesTableSeeder extends Seeder
{
    public function run()
    {
        $expenseTypes = [
            [1, 'Salario'],
            [2, 'Arriendo'],
            [3, 'Servicios Publicos'],
            [4, 'Implementos Oficina'],
            [5, 'Papeleria'],
            [6, 'Publicidad'],
            [7, 'Adecuacion del Local'],
            [8, 'Varios'],
            [9, 'Implementos Varios'],
            [10, 'Natillera'],
            [11, 'Cafeteria'],
            [12, 'Relojeria'],
            [13, 'Activos Fijos'],
            [14, 'Activos Corrientes'],
            [15, 'Almacenamiento'],
            [16, 'Sueldo Carlos'],
            [17, 'Inversiones'],
        ];

        if (!ExpenseType::find(17)) {
            foreach ($expenseTypes as $expenseType) {
                ExpenseType::create(['name' => $expenseType[1]]);
            }
        }
    }
}
