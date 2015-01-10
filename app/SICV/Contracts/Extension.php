<?php namespace SICV\Contracts;

use Eloquent;

class Extension extends Eloquent {

    protected $table = 'extensions';
    protected $fillable = [
        'amount',
        'created_at'
    ];

    public function amount(){
        return $this->amount;
    }

    public function contractId(){
        return $this->contract_id;
    }

    public function createdAt(){
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