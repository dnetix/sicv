<?php

use SICV\Articles\ArticleType;

class ArticleTypesTableSeeder extends Seeder {

	public function run()
	{
		$types = [
			[1, 'Sin Definir', null],
			[2, 'Sin Definir', 1],
			[3, 'Metales Preciosos', null],
			[4, 'Oro', 3],
			[5, 'Plata', 3],
			[6, 'Vehículos', null],
			[7, 'Bicicletas', 6],
			[8, 'Bicicleta TodoTerreno', 6],
			[9, 'Bicicleta Cross', 6],
			[10, 'Bicicleta Ruta', 6],
			[11, 'Electrodomesticos', null],
			[12, 'Nevera', 11],
			[13, 'Estufa', 11],
			[14, 'Tecnologia', null],
			[15, 'Televisor', 14],
			[16, 'Computadores', 14],
			[17, 'Computador Portatil', 16],
			[18, 'Computador Escritorio', 16],
			[19, 'Celular', 14],
			[20, 'Licuadora', 11],
			[21, 'Camara Digital', 14],
			[22, 'Reproductor DVD', 14],
			[23, 'Motocicleta', 6],
			[24, 'Herramientas', null],
			[25, 'Herramientas General', 24],
			[26, 'Equipo de Sonido', 14],
			[27, 'Lavadora', 11],
			[28, 'Consola de Juegos', 14],
			[29, 'PlayStation 1', 28],
			[30, 'PlayStation 2', 28],
			[31, 'PlayStation 3', 28],
			[32, 'PlayStation 4', 28],
			[33, 'Xbox', 28],
			[34, 'Xbox 360', 28],
			[35, 'Xbox One', 28],
			[36, 'PSP', 28],
		];

		foreach($types as $type){
			ArticleType::create([
				'article_type' => $type[1],
				'article_type_id' => $type[2]
			]);
		}
	}

}