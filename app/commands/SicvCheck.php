<?php

use Illuminate\Console\Command;
use SICV\Articles\Article;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SicvCheck extends Command {

	protected $name = 'sicv:check';

	protected $description = 'Retorna las sugerencias necesarias para falta de integridades en la base de datos.';

	public function __construct()
	{
		parent::__construct();
	}

	public function fire()
	{
		// Recommend to check for articles ID when it's gold and not have set the weight
		$articles = Article::all();
		$goldWeightRegex = '/([\d.,]+)\s?(?=(?:Gr\.|gr|gramos|g))/i';
		echo "Checking for Gold Articles without Weight\n";
		$matches = [];
		foreach($articles as $article){
			if($article->isGold() && empty($article->weight())){
				$goldWeightSuggestion = null;
				preg_match($goldWeightRegex, $article->description(), $matches);
				if(isset($matches[1])){
					$goldWeightSuggestion = str_replace(',', '.', $matches[1]);
				}
				if($this->option('accept') && !empty($goldWeightSuggestion)){
					$article->setWeight($goldWeightSuggestion)->update();
				}
				echo "\t{$article->id()} : {$article->description()} [{$goldWeightSuggestion}]\n";
			}
		}
	}

	protected function getArguments()
	{
		return array(
		);
	}

	protected function getOptions()
	{
		return array(
			array('accept', null, InputOption::VALUE_OPTIONAL, 'If it\'s true, replaces the values on the database.', false),
		);
	}

}
