<?php namespace SICV\Articles;

use Eloquent;

class ArticleType extends Eloquent  {

	protected $table = 'article_types';
	public $timestamps = false;

	const GOLD_ID = 3;

	protected $fillable = [
		'article_type',
		'article_type_id'
	];

	public function id(){
		return $this->id;
	}

	public function name(){
		return $this->article_type;
	}

	public function parentId(){
		return $this->article_type_id;
	}

	public function articles(){
		return $this->hasMany(Article::class);
	}

	public function parent(){
		return $this->belongsTo(ArticleType::class, 'article_type_id');
	}

	public function toString(){
		return $this->name();
	}

	public static function isGold($article_type_id){
		return $article_type_id == self::GOLD_ID;
	}

}
