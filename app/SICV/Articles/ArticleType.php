<?php namespace SICV\Articles;

use Eloquent;

class ArticleType extends Eloquent  {

	protected $table = 'article_types';

	public $timestamps = false;

	public function articles(){
		return $this->hasMany(Article::class);
	}

	public function parent(){
		return $this->belongsTo(ArticleType::class, 'article_type_id');
	}

}
