<?php namespace SICV\Contracts;

use Eloquent;
use SICV\Articles\Article;
use SICV\Clients\Client;
use SICV\Presenters\ContractPresenter;
use SICV\Users\User;
use SICV\Utils\Dates\DateHelper;
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

	public function id(){
		return $this->id;
	}

	public function amount(){
		return $this->amount;
	}

	public function payment(){
		return ceil($this->amount() * ($this->percentage / 100));
	}

	public function userId(){
		return $this->user_id;
	}

	public function clientId(){
		return $this->client_id;
	}

	public function getCreatedAt(){
		return $this->created_at;
	}

	public function state(){
		return $this->state;
	}

	public function endDate(){
		return $this->end_date;
	}

	public function elapsedMonths(){
		return $this->elapsedDifference()->months();
	}

	public function elapsedDifference(){
		if($this->isActive()){
			return DateHelper::getDifference($this->getCreatedAt());
		}else{
			return DateHelper::getDifference($this->getCreatedAt(), $this->getEndDate());
		}
	}

	public function amountToTerminate(){
		if($this->isActive()){
			return $this->amount() + $this->duedExtensions();
		}
		return 0;
	}

	public function duedExtensions(){
		if($this->isActive()){
			$months = $this->elapsedMonths();
			if($months == 0){
				$months++;
			}
			return (($months * $this->payment()) - $this->totalExtensions());
		}
		return 0;
	}

	public function totalExtensions(){
		return $this->extensions->sum('amount');
	}

	public function isActive(){
		return $this->state() == ContractStates::ACTIVE;
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
