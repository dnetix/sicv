<?php

namespace App\Models\Clients;

use App\Models\Contracts\Contract;
use App\Models\Presenters\ClientNotePresenter;
use App\Models\Users\User;
use App\Models\Utils\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Model;

class ClientNote extends Model
{
    use PresentableTrait;

    public const NI_HIGH = 'danger';
    public const NI_MEDIUM = 'warning';
    public const NI_LOW = 'info';

    protected $presenter = ClientNotePresenter::class;

    protected $table = 'client_notes';

    protected $fillable = [
        'note',
        'client_id',
        'user_id',
        'contract_id',
        'importance',
    ];

    public function id()
    {
        return $this->id;
    }

    public function note()
    {
        return $this->note;
    }

    public function clientId()
    {
        return $this->client_id;
    }

    public function userId()
    {
        return $this->user_id;
    }

    public function contractId()
    {
        return $this->contract_id;
    }

    public function createdAt()
    {
        return $this->created_at;
    }

    public function importance()
    {
        return $this->importance;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
