<?php namespace SICV\Articles;

use Eloquent;

class Article extends Eloquent  {

	protected $table = 'articles';

	public $timestamps = false;

	public function articletype(){
		return $this->belongsTo(ArticleType::class);
	}

}
