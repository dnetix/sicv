<?php namespace SICV\Users;

use Eloquent;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use SICV\Clients\ClientNote;
use SICV\Contracts\Contract;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table = 'users';
	protected $hidden = array('password', 'remember_token');
	//TODO check for password mass assigment active just for migration purposes
	protected $fillable = [
		'username',
		'name',
		'email',
		'role',
		'active',
	];
	
	public function id(){
		return $this->id;
	}

	public function name(){
		return $this->name;
	}

	public function setPassword($password){
		$this->password = $password;
		return $this;
	}

	/**
	 * When it try to set the password automatically hashit
	 * @param $value
	 */
	public function setPasswordAttribute($value){
		$this->attributes['password'] = \Hash::make($value);
	}

	/* ----------- Relationships --------------- */

	public function contracts(){
		return $this->hasMany(Contract::class);
	}

	public function clientNotes(){
		return $this->hasMany(ClientNote::class);
	}

	public function annuls(){
		return $this->hasMany(Annul::class);
	}

}
