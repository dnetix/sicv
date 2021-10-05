<?php

namespace Database\Seeders;

use App\Models\Articles\ArticleType;
use Illuminate\Database\Seeder;

class ArticleTypesTableSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Seeding article types');

        $types = [
            [1, 'Sin Definir', null],
            [2, 'Metales Preciosos', null],
            [3, 'Oro', 2],
            [4, 'Plata', 2],
            [5, 'Vehículos', null],
            [6, 'Bicicletas', 5],
            [7, 'Bicicleta TodoTerreno', 5],
            [8, 'Bicicleta Cross', 5],
            [9, 'Bicicleta Ruta', 5],
            [10, 'Electrodomesticos', null],
            [11, 'Nevera', 10],
            [12, 'Estufa', 10],
            [13, 'Tecnologia', null],
            [14, 'Televisor', 13],
            [15, 'Computadores', 13],
            [16, 'Computador Portatil', 15],
            [17, 'Computador Escritorio', 15],
            [18, 'Celular', 13],
            [19, 'Licuadora', 10],
            [20, 'Camara Digital', 13],
            [21, 'Reproductor DVD', 13],
            [22, 'Motocicleta', 5],
            [23, 'Herramientas', null],
            [24, 'Herramientas General', 23],
            [25, 'Equipo de Sonido', 13],
            [26, 'Lavadora', 10],
            [27, 'Consola de Juegos', 13],
            [28, 'PlayStation 1', 27],
            [29, 'PlayStation 2', 27],
            [30, 'PlayStation 3', 27],
            [31, 'PlayStation 4', 27],
            [32, 'Xbox', 27],
            [33, 'Xbox 360', 27],
            [34, 'Xbox One', 27],
            [35, 'PSP', 27],
        ];

        $article = ArticleType::find(1);

        if (!$article) {
            foreach ($types as $type) {
                ArticleType::create([
                    'article_type' => $type[1],
                    'article_type_id' => $type[2],
                ]);
            }
        } else {
            $this->command->info('Already seeded types');
        }
    }
}
