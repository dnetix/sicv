<?php namespace SICV\Clients;

use Eloquent;
use SICV\Contracts\Contract;
use SICV\Presenters\ClientPresenter;
use SICV\Utils\Presenters\PresentableTrait;

class Client extends Eloquent {

    protected $presenter = ClientPresenter::class;
    use PresentableTrait;

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
        'flagged'
    ];

    public function id() {
        return $this->id;
    }

    public function name() {
        return $this->name;
    }

    public function idNumber() {
        return $this->id_number;
    }

    public function idType() {
        return $this->id_type;
    }

    public function idExpedition() {
        return $this->id_expedition;
    }

    public function address() {
        return $this->address;
    }

    public function cellNumber() {
        return $this->cell_number;
    }

    public function phoneNumber() {
        return $this->phone_number;
    }

    public function email() {
        return $this->email;
    }

    public function city() {
        return $this->city;
    }

    public function isFlagged(){
        return $this->flagged == 1;
    }

    /* ----------- Relationships --------------- */

    public function contracts(){
        return $this->hasMany(Contract::class)->with('articles');
    }

    public function notes(){
        return $this->hasMany(ClientNote::class);
    }

}