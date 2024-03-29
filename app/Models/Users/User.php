<?php

namespace App\Models\Users;

use App\Models\Clients\ClientNote;
use App\Models\Contracts\Annul;
use App\Models\Contracts\Contract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'users';

    protected $hidden = ['password', 'remember_token'];

    protected $fillable = [
        'username',
        'name',
        'email',
        'role',
        'active',
        'email_verified_at',
        'remember_token',
    ];

    public function id()
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function name()
    {
        return $this->name;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * When it try to set the password automatically hashit.
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function clientNotes()
    {
        return $this->hasMany(ClientNote::class);
    }

    public function annuls()
    {
        return $this->hasMany(Annul::class);
    }
}
