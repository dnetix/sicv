<?php namespace SICV\Clients;

use Eloquent;
use SICV\Contracts\Contract;

class Client extends Eloquent {

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
        'city'
    ];

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getIdNumber() {
        return $this->id_number;
    }

    public function getIdType() {
        return $this->id_type;
    }

    public function getIdExpedition() {
        return $this->id_expedition;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCellNumber() {
        return $this->cell_number;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCity() {
        return $this->city;
    }

    public function contracts(){
        return $this->hasMany(Contract::class);
    }

}