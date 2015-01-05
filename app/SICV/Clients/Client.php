<?php  namespace SICV\Clients;

use Eloquent;

class Client extends Eloquent {

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'id_number',
        'id_type',
        'id_expedition',
        'address',
        'cellnumber',
        'phonenumber',
        'email',
        'city'
    ];

    public function getId(){
        return $this->id;
    }

}