<?php

namespace App\Models\Clients;

use App\Models\Contracts\Contract;
use App\Models\Utils\Presenters\PresentableTrait;
use App\Presenters\ClientPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    use PresentableTrait;

    protected $presenter = ClientPresenter::class;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'id_number',
        'id_type',
        'id_expedition',
        'address',
        'cell_number',
        'phone_number',
        'email',
        'city',
        'flagged',
    ];

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function idNumber()
    {
        return $this->id_number;
    }

    public function idType()
    {
        return $this->id_type;
    }

    public function idExpedition()
    {
        return $this->id_expedition;
    }

    public function address()
    {
        return $this->address;
    }

    public function cellNumber()
    {
        return $this->cell_number;
    }

    public function phoneNumber()
    {
        return $this->phone_number;
    }

    public function email()
    {
        return $this->email;
    }

    public function city()
    {
        return $this->city;
    }

    public function isFlagged()
    {
        return $this->flagged == 1;
    }

    /* ----------- Relationships --------------- */

    public function contracts()
    {
        return $this->hasMany(Contract::class)->with('articles');
    }

    public function notes()
    {
        return $this->hasMany(ClientNote::class);
    }
}
