<?php

use SICV\Articles\ArticleType;

class ArticleTypesTableSeeder extends Seeder {

	public function run()
	{
		$types = [
			[1, 'Metales Preciosos', null],
			[2, 'Oro', 1],
			[3, 'Plata', 1],
			[4, 'Vehículos', null],
			[5, 'Bicicletas', 4],
			[6, 'Bicicleta TodoTerreno', 5],
			[7, 'Bicicleta Cross', 5],
			[8, 'Bicicleta Ruta', 5],
			[9, 'Electrodomesticos', null],
			[10, 'Nevera', 9],
			[11, 'Estufa', 9],
			[12, 'Tecnologia', null],
			[13, 'Televisor', 12],
			[14, 'Computadores', 12],
			[15, 'Computador Portatil', 14],
			[16, 'Computador Escritorio', 14],
			[17, 'Celular', 12]
		];

		foreach($types as $type){
			ArticleType::create([
				'article_type' => $type[1],
				'article_type_id' => $type[2]
			]);
		}
	}

}