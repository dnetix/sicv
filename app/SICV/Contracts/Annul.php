<?php  namespace SICV\Contracts;

use Eloquent;
use SICV\Presenters\AnnulPresenter;
use SICV\Users\User;
use SICV\Utils\Presenters\PresentableTrait;

class Annul extends Eloquent {

    protected $presenter = AnnulPresenter::class;
    use PresentableTrait;

    protected $table = 'annuls';
    protected $fillable = [
        'created_at',
        'note',
        'original_amount',
        'contract_id',
        'user_id'
    ];
    public $timestamps = false;

    public function createdAt(){
        return $this->created_at;
    }

    public function note(){
        return $this->note;
    }

    public function originalAmount(){
        return $this->original_amount;
    }

    public function contractId(){
        return $this->contract_id;
    }

    public function userId(){
        return $this->user_id;
    }

    /* ----------- Relationships --------------- */

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}