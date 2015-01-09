<?php namespace SICV\Contracts;

use Eloquent;

class Extension extends Eloquent {

    protected $table = 'extensions';
    protected $fillable = [
        'amount',
        'created_at'
    ];

    public function getAmount(){
        return $this->amount;
    }

    public function getContractId(){
        return $this->contract_id;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    // Trying to remove updated_at, let's see
    public function getDates(){
        return ['created_at'];
    }

}