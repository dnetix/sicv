<?php namespace SICV\Articles;

use Eloquent;

class ArticleType extends Eloquent  {

	protected $table = 'article_types';

	protected $fillable = [
		'article_type',
		'article_type_id'
	];

	public $timestamps = false;

	public function getName(){
		return $this->article_type;
	}

	public function articles(){
		return $this->hasMany(Article::class);
	}

	public function parent(){
		return $this->belongsTo(ArticleType::class, 'article_type_id');
	}

}
