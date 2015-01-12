<?php namespace SICV\Users;

use Eloquent;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use SICV\Contracts\Contract;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/**
	 * When it try to set the password automatically hashit
	 * @param $value
	 */
	public function setPasswordAttribute($value){
		$this->attributes['password'] = \Hash::make($value);
	}

	public function name(){
		return $this->name;
	}

	public function contracts(){
		return $this->hasMany(Contract::class);
	}

	public function clientNotes(){
		// TO ClientNotes
	}

}
