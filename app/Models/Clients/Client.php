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
        'document',
        'document_type',
        'expedition_city',
        'address',
        'mobile',
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

    public function document()
    {
        return $this->document;
    }

    public function documentType()
    {
        return $this->document_type;
    }

    public function expeditionCity()
    {
        return $this->expedition_city;
    }

    public function address()
    {
        return $this->address;
    }

    public function mobile()
    {
        return $this->mobile;
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
