<?php namespace SICV\Contracts;

use Eloquent;
use SICV\Articles\Article;
use SICV\Clients\Client;
use SICV\Users\User;

class Contract extends Eloquent  {

	protected $table = 'contracts';

	protected $fillable = [
		'user_id',
		'client_id',
		'months',
		'percentage',
		'amount',
		'state'
	];

	public function client(){
		return $this->belongsTo(Client::class);
	}

	public function user(){
		return $this->belongsTo(User::class);
	}

	public function articles(){
		return $this->belongsToMany(Article::class);
	}

}
