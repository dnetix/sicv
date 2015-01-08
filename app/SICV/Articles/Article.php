<?php namespace SICV\Articles;

use Eloquent;
use SICV\Contracts\Contract;

class Article extends Eloquent  {

	protected $table = 'articles';

	protected $fillable = [
		'description',
		'weight',
		'article_type_id'
	];

	public $timestamps = false;

	public function getId(){
		return $this->id;
	}

	public function getDescription(){
		return $this->description;
	}

	public function articleType(){
		return $this->belongsTo(ArticleType::class);
	}

	public function contracts(){
		return $this->belongsToMany(Contract::class);
	}

}
