<?php namespace SICV\Contracts;

use Eloquent;
use SICV\Presenters\ExtensionPresenter;
use SICV\Utils\Presenters\PresentableTrait;

class Extension extends Eloquent {

    protected $presenter = ExtensionPresenter::class;
    use PresentableTrait;

    protected $table = 'extensions';
    protected $fillable = [
        'user_id',
        'contract_id',
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