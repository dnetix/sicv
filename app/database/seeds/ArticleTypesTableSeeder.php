<?php

use SICV\Articles\ArticleType;

class ArticleTypesTableSeeder extends Seeder {

	public function run()
	{
		$types = [
			[1, 'Metales Preciosos', null],
			[2, 'Oro', 1],
			[3, 'Plata', 1],
			[4, 'Electrodomesticos', null],
			[5, 'Nevera', 4],
			[6, 'Estufa', 4],
			[7, 'Tecnologia', null],
			[8, 'Televisor', 7],
			[9, 'Computadores', 7],
			[10, 'Computador Portatil', 9],
			[11, 'Computador Escritorio', 9],
			[12, 'Celular', 7]
		];

		foreach($types as $type){
			ArticleType::create([
				'article_type' => $type[1],
				'article_type_id' => $type[2]
			]);
		}
	}

}