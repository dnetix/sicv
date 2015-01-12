<?php namespace SICV\Clients;

use Eloquent;
use SICV\Contracts\Contract;
use SICV\Presenters\ClientNotePresenter;
use SICV\Users\User;
use SICV\Utils\Presenters\PresentableTrait;

class ClientNote extends Eloquent {

    const NI_HIGH = 'danger';
    const NI_MEDIUM = 'warning';
    const NI_LOW = 'info';

    protected $presenter = ClientNotePresenter::class;
    use PresentableTrait;

    protected $table = 'client_notes';

    protected $fillable = [
        'note',
        'client_id',
        'user_id',
        'contract_id',
        'importance'
    ];

    public function id() {
        return $this->id;
    }

    public function note() {
        return $this->note;
    }

    public function clientId() {
        return $this->client_id;
    }

    public function userId() {
        return $this->user_id;
    }

    public function contractId() {
        return $this->contract_id;
    }

    public function createdAt(){
        return $this->created_at;
    }

    public function importance() {
        return $this->importance;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

}