<?php namespace SICV\Contracts;

use Eloquent;
use SICV\Clients\Client;
use SICV\Users\User;

class Contract extends Eloquent  {

	protected $table = 'contracts';

	public function client(){
		return $this->belongsTo(Client::class);
	}

	public function user(){
		return $this->belongsTo(User::class);
	}

}
