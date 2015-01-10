<?php namespace SICV\Articles;

use Eloquent;
use SICV\Contracts\Contract;
use SICV\Presenters\ArticlePresenter;
use SICV\Utils\Presenters\PresentableTrait;

class Article extends Eloquent  {

	protected $presenter = ArticlePresenter::class;
	use PresentableTrait;

	protected $table = 'articles';
	public $timestamps = false;

	protected $fillable = [
		'description',
		'weight',
		'article_type_id'
	];

	public function getId(){
		return $this->id;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getWeight(){
		return $this->weight;
	}

	public function articleType(){
		return $this->belongsTo(ArticleType::class);
	}

	public function contracts(){
		return $this->belongsToMany(Contract::class);
	}

}
