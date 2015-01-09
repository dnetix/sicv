<?php namespace SICV\Contracts;

use Eloquent;
use SICV\Articles\Article;
use SICV\Clients\Client;
use SICV\Presenters\ContractPresenter;
use SICV\Users\User;
use SICV\Utils\Presenters\PresentableTrait;

class Contract extends Eloquent  {

	protected $presenter = ContractPresenter::class;
	use PresentableTrait;

	protected $table = 'contracts';

	protected $fillable = [
		'user_id',
		'client_id',
		'months',
		'percentage',
		'amount',
		'state'
	];

	public function getId(){
		return $this->id;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function getUserId(){
		return $this->user_id;
	}

	public function getClientId(){
		return $this->client_id;
	}

	public function client(){
		return $this->belongsTo(Client::class);
	}

	public function user(){
		return $this->belongsTo(User::class);
	}

	public function articles(){
		return $this->belongsToMany(Article::class);
	}

	public function extensions(){
		return $this->hasMany(Extension::class);
	}

}
